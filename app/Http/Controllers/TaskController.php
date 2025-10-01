<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|regex:/^(?:(?![<>&]).)*$/',
            'description' => 'nullable|string|max:1000',
            'status' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
        ]);

        $validated['description'] = strip_tags($validated['description'] ?? '');

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|regex:/^(?:(?![<>&]).)*$/',
            'description' => 'nullable|string|max:1000',
            'status' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
        ]);

        $validated['description'] = strip_tags($validated['description'] ?? '');

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}
