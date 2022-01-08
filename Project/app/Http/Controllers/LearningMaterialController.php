<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Stroage;


use App\Models\LearningMaterial;
use File;
class LearningMaterialController extends Controller
{

      public function store(Request $request)
      {

        $request->validate([
            'file'=>'required|mimes:jpg,pdf,ppt,docx|max:2048',
          //  'file'=>'required|image',
         ]);

        $name = $request->file('file')->getClientOriginalName();

        $path =  $request->file('file')->storeAs('uploads', $name, 'public');


        $data = new LearningMaterial;
        $data->course_id = request('course_id');
        $data->fileName = $name;
        $data->path = $path;
        $save = $data->save();

        if( $save ){
            return redirect()->back()->with('success', 'Learning Material Uploaded Successfully', ['data' => $data]);
        }else{
          return redirect()->back()->with('fail', 'wrong inputs');
        }





      }


      public function view($id)
      {

       $data=LearningMaterial::find($id);

       return view('tutor.dashboard.viewLearningMaterial',compact('data'));
      }

      public function viewForLearner($id)
      {

       $data=LearningMaterial::find($id);

       return view('learner.dashboard.viewLearningMaterial',compact('data'));
      }


      public function download(Request $request,$file)
      {

        return response()->download(public_path('/storage/uploads/'.$file));
      }

      public function destroy($id)
      {
        $del = LearningMaterial::find($id);
        File::delete(public_path('/storage/uploads/'.$del->fileName));
        $del->delete();
        return redirect()->back()->with('delete, Record Deleted');
      }

}
