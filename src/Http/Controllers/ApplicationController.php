<?php

namespace Risky2k1\ApplicationManager\Http\Controllers;

use Risky2k1\ApplicationManager\Jobs\ExportLeavingApplicationJob;
use Risky2k1\ApplicationManager\Jobs\ExportRequestApplicationJob;
use Risky2k1\ApplicationManager\Models\Application;
use Risky2k1\ApplicationManager\Models\States\Application\Pending;
use Risky2k1\ApplicationManager\Models\States\Application\Approved;
use Risky2k1\ApplicationManager\Models\States\Application\Declined;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

class ApplicationController extends Controller
{
    public function index(Request $request, string $type)
    {
        $query = Application::query();

        if (!empty($request->input('keyword'))) {
            $keyword = $request->input('keyword');
            $query->whereHas('user', function ($userQuery) use ($keyword) {
                $userQuery->where('name', 'like', '%'.$keyword.'%');
            });
        }

        if (!empty($request->input('state'))) {
            $query->where('state', $request->input('state'));
        }

        $applications = $query->where('type', $type)->latest()->paginate()->withQueryString();

        return view('pages.applications.index', compact('applications'));
    }

    public function create()
    {
        $users = User::all();
        return view('pages.applications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason' => ['required', 'string'],
            'is_paid_leave' => ['boolean', 'nullable'],
            'row_repeater' => ['array', 'nullable'],
            'row_repeater.*' => ['array', 'nullable'],
            'description' => ['string', 'nullable'],
            'reviewer_id' => ['required', 'string'],
            'attached_files' => ['file'],

            'name' => ['string', 'nullable'],
            'money_amount' => ['numeric', 'min:0', 'nullable'],
            'bank_account' => ['string', 'nullable'],
            'delivery_time' => ['string', 'nullable'],
            'delivery_date' => ['nullable', 'date_format:d/m/Y'],
        ]);

        $application = Application::create([
            'reason' => $request->input('reason'),
            'code' => Application::generateCode(1),
            'is_paid_leave' => $request->input('is_paid_leave'),
            'description' => $request->input('description'),
            'reviewer_id' => $request->input('reviewer_id'),
            'user_id' => Auth::id(),
            'state' => Pending::class,
            'type' => $request->route('type'),
            'company_id' => session('company_id') ?? 1,

            'name' => $request->input('name'),
            'money_amount' => $request->input('money_amount'),
            'bank_account' => $request->input('bank_account'),
            'delivery_time' => $request->input('delivery_time'),
            'delivery_date' => carbon($request->input('delivery_date')),
        ]);

        if ($request->hasFile('attached_files')) {
            $file = $request->file('attached_files');
            $application->update([
                'attached_files' => Storage::disk('public')->putFileAs('application_attached_files/'.$application->user_id.'/'.$application->id, $file, $file->getClientOriginalName()),
            ]);
        }
        if ($request->has('row_repeater')) {
            foreach ($request->input('row_repeater') as $dayOff) {
                Validator::make($dayOff, [
                    'start_time' => ['nullable', 'date_format:d/m/Y'],
                    'start_shift' => ['nullable', 'string', 'max:255'],
                    'end_time' => ['nullable', 'date_format:d/m/Y'],
                    'end_shift' => ['nullable', 'string', 'max:255'],
                ])->validate();
                $application->dayOffs()->create([
                    'start_time' => carbon($dayOff['start_date']),
                    'start_shift' => $dayOff['start_shift'],
                    'end_time' => carbon($dayOff['end_date']),
                    'end_shift' => $dayOff['end_shift'],
                ]);
            }
        }
        if (!empty($request->consider_id)) {
            foreach ($request->consider_id as $considerId) {
                $consider = User::findOrFail($considerId);
                $application->considers()->toggle($consider);
            }
        }

        toastr()->success('Đơn của bạn đã được tạo thành công');

        return redirect()->route('applications.index', ['type' => $request->route('type')]);
    }

    public function edit(Request $request, Application $application)
    {
        $users = User::all();
        $type = $application->type;
        return view('pages.applications.edit', compact('application', 'type', 'users'));
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'reason' => ['required', 'string'],
            'is_paid_leave' => ['boolean'],
            'row_repeater' => ['array', 'nullable'],
            'row_repeater.*' => ['array', 'nullable'],
            'description' => ['string', 'nullable'],
            'reviewer_id' => ['required', 'string'],
            'attached_files' => ['file'],

            'name' => ['string', 'nullable'],
            'money_amount' => ['numeric', 'min:0', 'nullable'],
            'bank_account' => ['string', 'nullable'],
            'delivery_time' => ['string', 'nullable'],
            'delivery_date' => ['nullable', 'date_format:d/m/Y'],
        ]);

        $application->update([
            'reason' => $request->input('reason'),
            'is_paid_leave' => $request->input('is_paid_leave'),
            'description' => $request->input('description'),
            'reviewer_id' => $request->input('reviewer_id'),
            'user_id' => Auth::id(),

            'name' => $request->input('name'),
            'money_amount' => $request->input('money_amount'),
            'bank_account' => $request->input('bank_account'),
            'delivery_time' => $request->input('delivery_time'),
            'delivery_date' => carbon($request->input('delivery_date')),
        ]);

        if ($request->hasFile('attached_files')) {
            $file = $request->file('attached_files');
            $application->update([
                'attached_files' => Storage::disk('public')->putFileAs('application_attached_files/'.$application->user_id.'/'.$application->id, $file, $file->getClientOriginalName()),
            ]);
        }
        if ($request->has('row_repeater')) {
            foreach ($request->input('row_repeater') as $dayOff) {
                Validator::make($dayOff, [
                    'start_time' => ['nullable', 'date_format:d/m/Y'],
                    'start_shift' => ['nullable', 'string', 'max:255'],
                    'end_time' => ['nullable', 'date_format:d/m/Y'],
                    'end_shift' => ['nullable', 'string', 'max:255'],
                ])->validate();
                $application->dayOffs()->updateOrCreate(
                    ['id' => $dayOff['id']],
                    [
                        'start_time' => carbon($dayOff['start_date']),
                        'start_shift' => $dayOff['start_shift'],
                        'end_time' => carbon($dayOff['end_date']),
                        'end_shift' => $dayOff['end_shift'],
                    ]
                );
            }
        }
        if (!empty($request->consider_id)) {
            foreach ($request->consider_id as $considerId) {
                $consider = User::findOrFail($considerId);
                $application->considers()->toggle($consider);
            }
        }
        toastr()->success('Cập nhật đơn từ thành công');

        return redirect()->route('applications.index', ['type' => $application->type]);
    }

    public function updateApplicationState(Request $request)
    {
        $application = Application::findOrFail($request->application_id);
        if (!empty($request->state) && $application->isPending) {
            try {
                switch ($request->state) {
                    case ('declined'):
                        $application->state->transitionTo(Declined::class);
                        break;
                    default:
                        $application->state->transitionTo(Approved::class);
                }
                toastr()->success('Cập nhật trạng thái đơn từ thành công');
            } catch (CouldNotPerformTransition $exception) {
                toastr()->error($exception->getMessage());
            }
        }
        return redirect()->route('applications.index', ['type' => $application->type]);
    }

    public function export(string $type)
    {
        if ($type == config('application-manager.application.default')) {
            ExportRequestApplicationJob::dispatch($type);
            if (file_exists(storage_path('app/exports/applications/Danh_sách_đơn_từ_đề_nghị.xlsx'))) {
                return response()->download(storage_path('app/exports/applications/Danh_sách_đơn_từ_đề_nghị.xlsx'));
            }

        } else {
            ExportLeavingApplicationJob::dispatch($type);
            if (file_exists(storage_path('app/exports/applications/Danh_sách_đơn_từ_xin_nghỉ.xlsx'))) {
                return response()->download(storage_path('app/exports/applications/Danh_sách_đơn_từ_xin_nghỉ.xlsx'));
            }
        }
    }
}
