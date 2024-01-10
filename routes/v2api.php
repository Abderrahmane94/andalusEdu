<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v2\Auth\AuthenticationController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::any('login', 'login');
    Route::get('user-demo-login/{role_id}', 'DemoUser');
    Route::post('forget-password', 'emailVerify');
    Route::get('general-settings', 'generalSettings');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('auth/logout', 'Auth\AuthenticationController@logout');

    Route::get('student-profile-details/{id}', 'Auth\AuthenticationController@studentProfile');
    Route::get('student-profile-edit/{id}', 'Auth\AuthenticationController@studentProfileEdit');
    Route::post('student-profile-update/{id}', 'Auth\AuthenticationController@studentProfileUpdate');
    Route::post('student-profile-img-update/{id}', 'Auth\AuthenticationController@studentProfileImgUpdate');
    Route::get('student-record', 'Auth\AuthenticationController@studentRecord');

    

    Route::get('profile-personal', 'Auth\AuthenticationController@profilePersonal');
    Route::get('profile-parents', 'Auth\AuthenticationController@profileParents');
    Route::get('profile-transport', 'Auth\AuthenticationController@profileTransport');
    Route::get('profile-others', 'Auth\AuthenticationController@profileOthers');
    Route::get('profile-documents', 'Auth\AuthenticationController@profileDocuments');
   

    Route::get('admin-teacher-homework', 'Homework\HomeworkController@adminTeacherhomework');
    Route::get('student-homework/{record_id}', 'Homework\HomeworkController@studentHomework');
    Route::get('parent-homework/{record_id}', 'Homework\HomeworkController@parentHomework');
    Route::get('student-homework-view/{class_id}/{section_id}/{homework}', 'Homework\HomeworkController@studentHomeworkView');
    Route::get('student-homework-file-download/{id}', 'Homework\HomeworkController@studentHomeworkFileDownload');
    Route::post('upload-homework-content', 'Homework\HomeworkController@uploadHomeworkContent');
    
    Route::get('student-assignment/{record_id}', 'Assignment\AssignmentController@studentAssignment');
    Route::get('student-assignment-file-download/{id}', 'Assignment\AssignmentController@studentAssignmentFileDownload');
    Route::get('upload-content-student-view/{id}', 'Assignment\AssignmentController@uploadContentView');

    Route::get('student-syllabus/{record_id}', 'Syllabus\SyllabusController@studentSyllabus');

    Route::get('student-others-download/{record_id}', 'OthersStudyMaterial\OthersStudyMaterialController@othersDownload');
    
    // Attendance 
    Route::post('student-attendance', 'Attendance\AttendanceController@stdAttendCurrMonth');
    Route::post('subject-wise-attendance', 'Attendance\AttendanceController@stdAttendSubjectWise');
    Route::get('record-wise-all-subjects/{record_id}', 'Attendance\AttendanceController@recWiseAllSubjects');

    // Notification
    Route::get('all-notification-list', 'Notification\NotificationController@allNotificationList');
    Route::get('view/all/notification/{id}', 'Notification\NotificationController@allNotificationMarkRead');

    //Fees
    Route::get('student-fees/{record_id}', 'Fees\StudentFeesController@studentFeesList');
    Route::get('add-fees-payment/{fees_invoice_id}', 'Fees\StudentFeesController@addFeesPayment');
    Route::get('gateway-service-charge', 'Fees\StudentFeesController@serviceCharge');
    Route::post('student-fees-payment-stores' , 'Fees\StudentFeesController@studentFeesPaymentStore');
    Route::get('student-fees-payment-stores' , 'Fees\StudentFeesController@studentFeesPaymentStore');
    Route::get('fees-invoice-view/{fee_invoice_id}', 'Fees\StudentFeesController@feesInvoiceView');
    Route::get('timeline/{student_id}', 'Timeline\TimelineController@stdTimeline');
    Route::get('remaining-leave', 'Leave\LeaveController@remainingLeave');
    Route::get('apply-leave', 'Leave\LeaveController@applyLeave');
    Route::post('student-leave-store', 'Leave\LeaveController@leaveStore');
    Route::get('student-leave-edit/{id}', 'Leave\LeaveController@studentLeaveEdit');
    Route::post('student-leave-update/{id}', 'Leave\LeaveController@update');
    Route::get('student-teacher/{record_id}', 'Teacher\TeacherController@studentTeacher');

    Route::get('student-library', 'Library\LibraryController@studentBookList');
    Route::get('student-book-issue', 'Library\LibraryController@studentBookIssue');
    Route::get('student-transport', 'Transport\TransportController@studentTransport');
    Route::get('student-dormitory', 'Dormitory\DormitoryController@studentDormitory');

    Route::get('student-class-routine/{user_id}/{record_id}', 'ClassRoutine\ClassRoutineController@studentClassRoutine');
    Route::get('student-exam/{record_id}', 'Exam\ExamController@studentExam');
    Route::get('student-exam-schedule', 'Exam\ExamController@studentExamSchedule');
    Route::get('student-noticeboard', 'NoticeBoard\NoticeBoardController@studentNoticeboard');
    Route::get('student-single-noticeboard/{id}', 'NoticeBoard\NoticeBoardController@studentSingleNoticeboard');


});
 

 