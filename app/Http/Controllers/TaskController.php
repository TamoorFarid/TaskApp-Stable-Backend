<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\activity;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function createTask(Request $request,$id){
        $validator = Validator::make($request->all(),[
            "title"=>"required | max:90",
            "summary"=>"max:250",
            "description"=>"required | max:255"
        ]);
        if($validator->fails()){
            return response()->json(["error"=>$validator->errors()]);
        }else{
            $activity = activity::find($id);
            if($activity){
                $task = new Task();
                $task->title = $request -> title;
                $task->summary = $request -> summary;
                $task->description = $request -> description;
                $activity->task()->save($task);
                return response()->json([
                    "success" => true,
                    "message" => "Task has been created successfully",
                    "task" => $task
                ]);
            }
        }
    }


    public function getTasksList($id){
        $tasks = activity::find($id)->task->where("isCompleted",false)->values();
        if($tasks){
            return response()->json([
                "success" => true,
                "message" => "Tasks has been found successfully",
                "data" => [
                    "tasks" => $tasks
                ]
            ]);
        }
        return $tasks;
    }
    
    public function getCompletedTasksList(){
        $tasks = task::where("isCompleted",true)->get();
        if($tasks){
            return response()->json([
                "success" => true,
                "message" => "Tasks has been found successfully",
                "data" => [
                    "tasks" => $tasks
                ]
            ]);
        }
        return $tasks;
    }

    public function deleteTask($id){
        $task = Task::find($id);
        if($task){
            $task->delete();
            return response()->json([
                "success" => true,
                "message" => "Tasks has been deleted successfully"
            ]);
        }
    }

    public function updateTask(Request $request, $id){
        $task = Task::find($id);
        if($task){
            $validator = Validator::make($request->all(),[
                "title"=>"required | max:90",
                "summary"=>"max:250",
                "description"=>"required | max:255"
            ]);
            if($validator->fails()){
                return response()->json(["error"=>$validator->errors()]);
            }else{
                $task->title = $request->title;
                $task->summary = $request->summary;
                $task->description = $request->description;
                $task->save();
                return response()->json([
                    "success" => true,
                    "message" => "Tasks has been updated successfully",
                ]);
            }
        }
    }

    public function updateTasksStatus($id,Request $request){
        $validator = Validator::make($request->all(),[
            "isCompleted" => "required"
        ]);
        if($validator->fails()){
            return response()->json(["error"=>$validator->errors()]);
        }else{
            $task = Task::find($id);
            if($task){
                $task->isCompleted = $request -> isCompleted;
                $task->save();
                return response()->json([
                    "success" => true,
                    "message" => "The task status has been updated successfully"
    
                ]);
            }
        }
    }
}
