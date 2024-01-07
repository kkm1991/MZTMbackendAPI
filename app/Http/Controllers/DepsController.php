<?php

namespace App\Http\Controllers;

use App\Models\deps;
use Illuminate\Http\Request;

class DepsController extends Controller
{
    public function list(){
        $deps=deps::get();
        return response()->json($deps, 200);
    }
}
