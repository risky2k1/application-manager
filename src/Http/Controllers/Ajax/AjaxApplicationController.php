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
}
