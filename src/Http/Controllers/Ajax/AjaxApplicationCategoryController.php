<?php

namespace Risky2k1\ApplicationManager\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Risky2k1\ApplicationManager\Http\Controllers\Controller;
use Risky2k1\ApplicationManager\Models\Application;
use Risky2k1\ApplicationManager\Models\ApplicationCategory;
use Illuminate\Validation\Rule;

class AjaxApplicationCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_key' => 'required|unique:application_categories,key|regex:/^[a-zA-Z]+(?:_[a-zA-Z]+)*$/',
            'category_name' => 'required|string',
        ], [
            'category_key.required' => 'Trường Key không được để trống.',
            'category_key.unique' => 'Trường Key đã tồn tại.',
            'category_key.regex' => 'Trường Key không đúng định dạng a_b_c.',
            'category_name.required' => 'Trường Name không được để trống.',
            'category_name.string' => 'Trường Name phải là chuỗi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validator->validate();

        $category = ApplicationCategory::create([
            'key' => $request->category_key,
            'name' => $request->category_name,
        ]);

        return response()->json([
            'category' => $category,
            'success' => 'Tạo thành công',
        ]);
    }

    public function update(Request $request)
    {
        $category = ApplicationCategory::findOrFail($request->category_id);
        $validator = Validator::make($request->all(), [
            'category_key' => [
                'required',
                'regex:/^[a-zA-Z]+(?:_[a-zA-Z]+)*$/',
                Rule::unique('application_categories', 'key')->ignore($category)
            ],
            'category_name' => 'required|string',
        ], [
            'category_key.required' => 'Trường Key không được để trống.',
            'category_key.unique' => 'Trường Key đã tồn tại.',
            'category_key.regex' => 'Trường Key không đúng định dạng a_b_c.',
            'category_name.required' => 'Trường Name không được để trống.',
            'category_name.string' => 'Trường Name phải là chuỗi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update([
            'key' => $request->category_key,
            'name' => $request->category_name,
        ]);

        return response()->json([
            'category' => $category,
            'success' => 'Cập nhật thành công',
        ]);
    }

    public function destroy(Request $request)
    {
        $category = ApplicationCategory::findOrFail($request->category_id);

        if ($category->applications->count() == 0) {
            $category->delete();
            return response()->json([
                'message' => 'Xóa thành công.'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Có đơn thuộc nhóm này.'
            ], 422);
        }
    }

}
