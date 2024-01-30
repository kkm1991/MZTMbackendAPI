<?php

namespace App\Http\Controllers;

use App\Models\deps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepsController extends Controller
{
    public function list(){
        $deps=deps::get();
        return response()->json($deps, 200);
    }
    public function edit(Request $request){
        $editdeps=deps::find($request->id);
        $editdeps->title=$request->title;
        $editdeps->save();

        return $this->list();

    }
    public function add(Request $request){
       $request->validate(['title'=>'required|string']);
        $newdep=new deps();
        $newdep->title=request()->title;
        $newdep->save();

        return $this->list();
    }
    public function delete(Request $request){
        $deps=deps::find($request->id);
        $deps->delete();
        return $this->list();
    }
}
