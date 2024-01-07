<?php

namespace App\Http\Controllers;

use App\Models\position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function list(){
        $positions=position::all();
        return response()->json($positions, 200 );
    }
}
