<?php

namespace Risky2k1\ApplicationManager\Http\Controllers;

use Risky2k1\ApplicationManager\Jobs\ExportLeavingApplicationJob;
use Risky2k1\ApplicationManager\Jobs\ExportRequestApplicationJob;
use Risky2k1\ApplicationManager\Models\Application;
use Risky2k1\ApplicationManager\Models\ApplicationCategory;
use Risky2k1\ApplicationManager\Models\States\Application\Pending;
use Risky2k1\ApplicationManager\Models\States\Application\Approved;
use Risky2k1\ApplicationManager\Models\States\Application\Declined;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Illuminate\Support\Fluent;
use ZipArchive;

class ApplicationController extends Controller
{
    public function index(Request $request, string $type)
    {
        $query = Application::query();

        if (!empty($request->input('keyword'))) {
            $keyword = $request->input('keyword');
            $query->whereHas('user', function ($userQuery) use ($keyword) {
                $userQuery->where('name', 'like', '%'.$keyword.'%')->orWhere('code', 'like', '%'.$keyword.'%');
            });
        }

        if (!empty($request->input('state'))) {
            if ($request->input('state') === 'deleted') {
                $query->onlyTrashed();
            } elseif ($request->input('state') !== 'all') {
                $query->where('state', $request->input('state'));
            }
        }

        $applications = $query->whereHas('category', function ($categoryQuery) use ($type) {
            $categoryQuery->where('key', $type);
        })->latest()->paginate()->withQueryString();

        $categories = ApplicationCategory::whereNull('parent_id')->get();

        return view('application-manager::applications.index', compact('applications', 'categories'));
    }

    public function create(Request $request)
    {
        $users = User::where('is_active', true)->get();
        $type = $request->route('type');

        return view('application-manager::applications.create', compact('users', 'type'));
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
            'attached_files' => ['json', 'nullable'],
            'name' => ['string', 'nullable', Rule::requiredIf($request->route('type') == config('application-manager.application.default'))],
            'money_amount' => ['numeric', 'min:0', 'nullable'],
            'bank_account' => ['string', 'nullable'],
            'delivery_time' => ['string', 'nullable'],
            'delivery_date' => ['nullable', 'date_format:d/m/Y'],
        ]);

        $application = Application::create([
            'reason' => $request->input('reason'),
            'code' => Application::generateCode(session('company_id'), $request->route('type')),
            'is_paid_leave' => $request->input('is_paid_leave'),
            'description' => $request->input('description'),
            'reviewer_id' => $request->input('reviewer_id'),
            'user_id' => Auth::id(),
            'proponent_id' => $request->input('proponent_id'),
            'state' => Pending::class,
            'company_id' => session('company_id'),
            'category_id' => ApplicationCategory::where('key', $request->route('type'))->first()->id,
            'name' => $request->input('name'),
            'money_amount' => $request->input('money_amount'),
            'bank_account' => $request->input('bank_account'),
            'delivery_time' => $request->input('delivery_time'),
            'delivery_date' => carbon($request->input('delivery_date')),
        ]);

        if (!empty($request->attached_files)) {
            $attachedFiles = json_decode($request->input('attached_files'), true);
            $filePaths = [];

            $destinationStoragePath = storage_path('public/application_attached_files'.'/user_'.$application->user_id.'/application_'.$application->id);
            $destinationPath = 'application_attached_files'.'/user_'.$application->user_id.'/application_'.$application->id;

            if (!file_exists($destinationStoragePath)) {
                mkdir($destinationStoragePath, 0777, true);
            }

            foreach ($attachedFiles as $file) {
                $movedFilePath = Storage::move(
                    $file['file_path'],
                    'public/'.$destinationPath.'/'.$file['original_file_name']);
                $filePaths[] = $destinationPath.'/'.$file['original_file_name'];
            }
            $application->update([
                'attached_files' => $filePaths,
            ]);
        }
        if ($request->has('row_repeater')) {
            foreach ($request->input('row_repeater') as $dayOff) {
                $validator = Validator::make($dayOff, [
                    'start_date' => ['nullable', 'date_format:d/m/Y'],
                    'start_shift' => ['nullable', 'string', 'max:255'],
                    'end_date' => ['nullable', 'date_format:d/m/Y', 'after_or_equal:start_date'],
                    'end_shift' => ['nullable', 'string', 'max:255'],
                ], [

                ], [
                    'end_date' => 'ngày kết thúc',
                    'start_date' => 'ngày bắt đầu',
                ]);
                $validator->sometimes('end_date', 'after_or_equal:start_date', function ($input) {
                    return !empty($input->start_time);
                });

                $validator->validate();

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
        $users = User::where('is_active', true)->get();

        $type = $application->category->key;

        $attachedFiles = $application->attached_files;
        return view('application-manager::applications.edit', compact('application', 'type', 'users', 'attachedFiles'));
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
            'attached_files' => ['json', 'nullable'],
            'name' => ['string', 'nullable', Rule::requiredIf($request->route('type') == config('application-manager.application.default'))],
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
            'proponent_id' => $request->input('proponent_id'),

            'name' => $request->input('name'),
            'money_amount' => $request->input('money_amount'),
            'bank_account' => $request->input('bank_account'),
            'delivery_time' => $request->input('delivery_time'),
            'delivery_date' => carbon($request->input('delivery_date')),
        ]);

        if (!empty($request->attached_files)) {
            $attachedFiles = json_decode($request->input('attached_files'), true);
            $filePaths = [];

            $destinationStoragePath = storage_path('public/application_attached_files'.'/user_'.$application->user_id.'/application_'.$application->id);
            $destinationPath = 'application_attached_files'.'/user_'.$application->user_id.'/application_'.$application->id;

            if (!empty($application->attached_files)) {
                foreach ($application->attached_files as $attachedFile) {
                    Storage::delete('public'.'/'.$attachedFile);
                }
            }

            if (!file_exists($destinationStoragePath)) {
                mkdir($destinationStoragePath, 0777, true);
            }

            foreach ($attachedFiles as $file) {
                $movedFilePath = Storage::move(
                    $file['file_path'],
                    'public/'.$destinationPath.'/'.$file['original_file_name']);
                $filePaths[] = $destinationPath.'/'.$file['original_file_name'];
            }
            $application->update([
                'attached_files' => $filePaths,
            ]);
        }

        if ($request->has('row_repeater')) {
            foreach ($request->input('row_repeater') as $dayOff) {
                $validator = Validator::make($dayOff, [
                    'start_date' => ['nullable', 'date_format:d/m/Y'],
                    'start_shift' => ['nullable', 'string', 'max:255'],
                    'end_date' => ['nullable', 'date_format:d/m/Y', 'after_or_equal:start_date'],
                    'end_shift' => ['nullable', 'string', 'max:255'],
                ], [

                ], [
                    'end_date' => 'ngày kết thúc',
                    'start_date' => 'ngày bắt đầu',
                ]);

                $validator->sometimes('end_date', 'after_or_equal:start_date', function ($input) {
                    return !empty($input->start_time);
                });

                $validator->validate();

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
                $application->considers()->syncWithoutDetaching($consider);
            }
        }
        toastr()->success('Cập nhật đơn từ thành công');

        return redirect()->route('applications.index', ['type' => $application->category->key]);
    }

    public function destroy(Application $application)
    {
        $type = $application->category->key;
        $application->deleteOrFail();
        toastr()->success('Xoá đơn từ thành công');
        return redirect()->route('applications.index', ['type' => $type]);
    }

    public function updateApplicationState(Request $request)
    {
        $application = Application::findOrFail($request->application_id);
        if (!empty($request->state) && $application->isPending && $application->reviewer_id == auth()->id() && !$application->trashed()) {
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
        return redirect()->route('applications.index', ['type' => $application->category->key]);
    }

    public function export(Request $request, string $type)
    {
        $dataToDispatch = [];
        if (!empty($request->state) && $request->input('state') !== 'all') {
            $dataToDispatch['state'] = $request->state;
        }
        $dataToDispatch['type'] = $type;
        if ($type == config('application-manager.application.default')) {
            ExportRequestApplicationJob::dispatch($dataToDispatch);
            if (file_exists(storage_path('app/exports/applications/Danh_sách_đơn_từ_đề_nghị.xlsx'))) {
                return response()->download(storage_path('app/exports/applications/Danh_sách_đơn_từ_đề_nghị.xlsx'));
            }
        } else {
            ExportLeavingApplicationJob::dispatch($dataToDispatch);
            if (file_exists(storage_path('app/exports/applications/Danh_sách_đơn_từ_xin_nghỉ.xlsx'))) {
                return response()->download(storage_path('app/exports/applications/Danh_sách_đơn_từ_xin_nghỉ.xlsx'));
            }
        }
    }

    public function downloadAttachedFiles(Application $application)
    {
        $zipFileName = 'Tệp đính kèm của đơn từ '.$application->code.'.zip';
        $zipFilePath = storage_path("app/public/application_attached_files/user_".$application->user->id."/application_".$application->id."/".$zipFileName);
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
            $folderPath = "public/application_attached_files/user_{$application->user->id}/application_{$application->id}";

            if (Storage::exists($folderPath)) {
                $files = Storage::files($folderPath);
            }

            foreach ($files as $file) {
                $relativePath = str_replace($folderPath.'/', '', $file);
                $zip->addFile(storage_path('app/'.$file), $relativePath);
            }
        }
        $zip->close();

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function restore(Application $application)
    {
        $application->restore();
        toastr()->success('Khôi phục thành công');
        return redirect()->route('applications.index', ['type' => $application->category->key]);
    }

    public function category()
    {
        $categories = ApplicationCategory::paginate();
        return view('application-manager::applications.category', compact('categories'));
    }

}
