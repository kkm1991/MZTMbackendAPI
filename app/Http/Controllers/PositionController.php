<?php

namespace App\Http\Controllers;

use App\Models\position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function list(){
        $positions=position::get();
        return response()->json($positions, 200 );
    }
    public function edit(Request $request){
        $editposition=position::find($request->id);
        $editposition->title=$request->title;
        $editposition->save();

        return $this->list();

    }
    public function add(Request $request){
        $newposition=new position();
        $newposition->title=$request->title;
        $newposition->save();
        return $this->list();
    }
    public function delete(Request $request){
        $pos=position::find($request->id);
        $pos->delete();
        return $this->list();
    }
}
