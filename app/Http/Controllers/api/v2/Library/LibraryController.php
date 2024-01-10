<?php

namespace App\Http\Controllers\api\v2\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\StudentBookListResource;
use App\Http\Resources\v2\StudentIssuedBookListResource;
use App\SmBook;
use App\SmBookIssue;
use App\SmLibraryMember;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function studentBookList()
    {
        $books = SmBook::where('active_status', 1)
            ->orderBy('id', 'DESC')
            ->where('school_id', Auth::user()->school_id)->get();
        $data = StudentBookListResource::collection($books);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function studentBookIssue()
    {
        $user = Auth::user();
        $student_detail = SmStudent::where('user_id', $user->id)->first();
        $library_member = SmLibraryMember::where('member_type', 2)->where('student_staff_id', $student_detail->user_id)->first();
        if (empty($library_member)) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'You are not library member ! Please contact with librarian',
            ];    
            return response()->json($response, 401);
        }
        
        $issueBooks = SmBookIssue::where('member_id', $library_member->student_staff_id)
            ->leftjoin('sm_books', 'sm_books.id', 'sm_book_issues.book_id')
            ->leftjoin('library_subjects', 'library_subjects.id', 'sm_books.book_subject_id')
            ->where('sm_book_issues.issue_status', 'I')
            ->where('sm_book_issues.school_id', Auth::user()->school_id)
            ->get();
        $data = StudentIssuedBookListResource::collection($issueBooks);

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];    
        return response()->json($response, 200);

    }
}
