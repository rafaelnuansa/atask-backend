<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->guard('api')->user();
        $tasks = Task::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully',
            'data'    => $tasks
        ], 200);
    }

    /**
     * Store a newly created task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'priority'    => 'required|in:low,medium,high',
        ]);

        // Response error validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Get the authenticated user
        $user = auth()->guard('api')->user();

        // Create a new task with the authenticated user's ID
        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'date'        => $request->date,
            'priority'    => $request->priority,
            'user_id'     => $user->id,
        ]);

        // Response with the created task
        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data'    => $task
        ], 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = auth()->guard('api')->user();
        $task = Task::where('id', $id)->where('user_id', $user->id)->first();

        if (is_null($task)) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task retrieved successfully',
            'data'    => $task
        ], 200);
    }

    /**
     * Update the specified task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = auth()->guard('api')->user();
        $task = Task::where('id', $id)->where('user_id', $user->id)->first();

        if (is_null($task)) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'date'        => 'sometimes|required|date',
            'priority'    => 'sometimes|required|in:low,medium,high',
        ]);

        // Response error validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Update the task
        $task->update($request->all());

        // Response with the updated task
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data'    => $task
        ], 200);
    }

    /**
     * Remove the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = auth()->guard('api')->user();
        $task = Task::where('id', $id)->where('user_id', $user->id)->first();

        if (is_null($task)) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        // Delete the task
        $task->delete();

        // Response with a success message
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ], 200);
    }
}
