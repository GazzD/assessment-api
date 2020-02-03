<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class QuestionsController extends Controller
{
    public function all()
    {
        return response()->json('All questions', Response::HTTP_OK);
    }
    
    public function add(Request $request)
    {
        return response()->json('Question created', Response::HTTP_CREATED);
    }

}
