<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id',1)->get();
        return response()->json([
            'data' => $tasks
        ], 200);
    }

    public function store(Request $request)
    {
        $validated =$request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::create([
            'user_id' => 1,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Create task',
            'data' => '$task'
        ],201);
    }

    public function show(string $id)
    {
        // $task = Task::where('user_id', 1)->findOrFail($id);
        $task = Task::find($id);

        if(!$task){
            return response()->json([
                'message' => 'Not found'
            ],404);
        }
        return response()->json([
            'data' => $task
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $task = Task::where('user_id', 1)->findOrFail($id);
        /**
        * $task = Task::where('user_id', 1)->find($id);
        * if (!$task) {
        *    return response()->json([
        *        'message' => 'タスクが見つかりません'
        *    ], 404);
        *}
        */
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);
        return response()->json([
            'message' => 'Update task',
            'data' => $task
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::where('user_id', 1)->findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }
}
