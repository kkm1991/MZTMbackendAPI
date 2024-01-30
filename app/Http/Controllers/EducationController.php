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
    public function edit(Request $request){
        $editedu=education::find($request->id);
        $editedu->title=$request->title;
        $editedu->save();

        return $this->list();

    }

    public function add(Request $request){
        $newedu=new education();
        $newedu->title=$request->title;
        $newedu->save();

        return $this->list();
    }
    public function delete(Request $request){
        $edu=education::find($request->id);
        $edu->delete();
        return $this->list();
    }
}
