<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Models\SmExpertTeacher;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmExpertTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
    }
    public function index()
    {
        try {
            $expertTeachers = SmExpertTeacher::where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.expert_teacher.expert_teacher', compact('expertTeachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
            'designation' => "required",
            'image' => "required|mimes:jpg,jpeg,png|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/expert_teacher/';
            $image = fileUpload($request->image, $destination);
            $expertTeacher = new SmExpertTeacher();
            $expertTeacher->name = $request->name;
            $expertTeacher->designation = $request->designation;
            $expertTeacher->image = $image;
            $expertTeacher->school_id = app('school')->id;
            $result = $expertTeacher->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('expert-teacher');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $expertTeachers = SmExpertTeacher::where('school_id', app('school')->id)->get();
            $add_expert_teacher = SmExpertTeacher::find($id);
            return view('backEnd.frontSettings.expert_teacher.expert_teacher', compact('add_expert_teacher', 'expertTeachers'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        if ($input['id']) {
            $validator = Validator::make($input, [
                'image' => "sometimes|nullable|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            ]);
        } else {
            $validator = Validator::make($input, [
                'name' => "required",
                'designation' => "required",
                'image' => "required|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/expert_teacher/';
            $expertTeacher = SmExpertTeacher::find($request->id);
            $expertTeacher->name = $request->name;
            $expertTeacher->designation = $request->designation;
            $expertTeacher->image = fileUpdate($expertTeacher->image, $request->image, $destination);
            $expertTeacher->school_id = app('school')->id;
            $result = $expertTeacher->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->route('expert-teacher');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $expertTeacher = SmExpertTeacher::find($id);
            return view('backEnd.frontSettings.expert_teacher.expert_teacher_delete_modal', compact('expertTeacher'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $expertTeacher = SmExpertTeacher::where('id', $id)->first();
            $expertTeacher->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
