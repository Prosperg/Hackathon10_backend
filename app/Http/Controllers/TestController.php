<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email"=>'required',
            "password"=>'required',
        ]);

        dd($request);
        $response = Http::asForm()->post("http://127.0.0.1:8000/api/auth/login",[
            "email"=>$request->email,
            "password"=>$request->password,
        ]);
        
        dd($response);
    }

    public function update(Request $request)
    {
        // dd($request);
        // $response = Http::withToken("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2OTI3OTE1MDMsImV4cCI6MTY5Mjc5NTEwMywibmJmIjoxNjkyNzkxNTAzLCJqdGkiOiJnMU5aM2hPejhya2ZpMFM2Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.3cTQvRN1okCJbH19983fGq_BrSULJvyRm7_2KQFmqPo")
        //             ->put("http://127.0.0.1:8000/api/product/20",[
        //                 "name"=>$request->name,
        //                 "description"=>$request->description,
        //                 "categorie_id"=>$request->categorie_id,
        //                 "price"=>$request->price,
        //             ]);
        // dd($response);
    }
}
