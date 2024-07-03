<?php

namespace App\Http\Controllers;

use App\Models\TaskDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = TaskDetails::where('user_id', Auth::user()->id)->get();
        return view('pages/dashboard/task', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable',
        ]);

        $TaskDetails = new TaskDetails([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->completed,
            'user_id' => Auth::user()->id,
        ]);

        $TaskDetails->save();

        return redirect()->route('taskview');
    }

    public function edit($id)
    {
        $task = TaskDetails::find($id);
        // $this->authorize('view', $task);

        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = TaskDetails::find($id);

        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable',
            'completed' => 'boolean',
        ]);

        $task->update($validatedData);

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = TaskDetails::find($id);
        $task->delete();
    
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
