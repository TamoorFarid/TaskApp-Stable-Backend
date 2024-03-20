<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\activity;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function createActivity(Request $request){
        $validator = Validator::make($request->all(),[
            "activityName" => 'required'
        ]);
        if($validator->fails()){
            return response()->json(["error"=>$validator->errors()]);
        }else{
            $activity = new activity();
            $activity -> activityName = $request ->activityName;
            $activity -> save();
            if($activity){
                return response()->json([
                    "success" => true,
                    "message" => "Activity created successfully",
                    "activity" => $activity
                ],200);
            }
        }
    }

    public function getAllActivities(){
        $activity = activity::all();
        if($activity){
            return response()->json([
                "success" => true,
                "message" => "Activities found successfully",
                "data" => [
                    "activities" => $activity
                ]
            ],200);
        }
    }

    public function deleteActivity($id){
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                "success" => false,
                "message" => "Activity not found",
            ], 404);
        }
        $activity->task()->delete();
        $activity->delete();
        return response()->json([
            "success" => true,
            "message" => "Activity and its related tasks have been deleted successfully",
        ], 200);
    }

    public function editActivity(Request $request,$id){
        $activity = activity::find($id);
        if($activity){
            $validator = Validator::make($request->all(),[
                "activityName" => "required | min:1"
            ]);
            if($validator->fails()){
                return response()->json(["errors"=>$validator->errors()]);
            }else{
                $activity->activityName = $request->activityName;
                $activity->save();

                return response()->json([
                    "success" => true,
                    "message" => "Activity has been updated successfully",
                ]); 
            }

        }
    }


}
