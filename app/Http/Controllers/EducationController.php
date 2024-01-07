<?php

namespace App\Http\Controllers;

use App\Models\education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function list(){
        $education=education::all();
        return response()->json($education, 200 );
    }
}
