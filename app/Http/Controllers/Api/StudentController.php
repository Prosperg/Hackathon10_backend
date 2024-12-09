<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $student = Student::create([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'password'=>$request->password,
            'email'=>$request->email
        ]);

        return response()->json('status', 200);
    }

    public function show()
    {
        $students = Student::all();
        return response()->json($students);
    }
}
