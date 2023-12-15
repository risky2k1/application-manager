<?php

namespace Risky2k1\ApplicationManager\Http\Controllers\Ajax;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Risky2k1\ApplicationManager\Http\Controllers\Controller;
use Risky2k1\ApplicationManager\Models\Application;

class AjaxApplicationController extends Controller
{
    public function deleteApplications(Request $request)
    {
        $selectedApplicationIds = $request->input('selected_applications_id');
        Application::whereIn('id', $selectedApplicationIds)->delete();

        return response()->json([
            'success' => 'Xoá thành công',
        ]);
    }

    public function restoreApplications(Request $request)
    {
        $selectedApplicationIds = $request->input('selected_applications_id');
        Application::whereIn('id', $selectedApplicationIds)->restore();

        return response()->json([
            'success' => 'Khôi phục thành công',
        ]);
    }

    public function uploadAttachedFiles(Request $request)
    {
        $path = storage_path('app/tmp/attached-files');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $uploadedFiles = [];
        foreach ($request->attached_files as $key => $file) {
            $attachedFile = Storage::putFileAs('tmp/attached-files', $file, $file->getClientOriginalName());

            $uploadedFiles[] = [
                'original_file_name' => $file->getClientOriginalName(),
                'attached_files' => $attachedFile,
            ];
        }
        return response()->json($uploadedFiles);
    }

    public function forceDeleteApplications(Request $request)
    {
        $selectedApplicationIds = $request->input('selected_applications_id');
        Application::whereIn('id', $selectedApplicationIds)->forceDelete();

        return response()->json([
            'success' => 'Xoá vĩnh viễn tất cả thành công',
        ]);
    }

}
