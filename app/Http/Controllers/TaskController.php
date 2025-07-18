<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section' => 'required|string',
            'name' => 'required|string|min:3|max:255',
            'complete' => 'nullable|boolean'
            ], [
                'section.required' => '⚠️ Please select a section',
                'name.required' => '⚠️ Please enter a task name between 3 and 255 characters long.'
        ]);

        Auth::user()->tasks()->create($validated);

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'section' => 'required|string',
            'name' => 'required|string|min:3|max:255',
            'complete' => 'nullable|boolean'
        ], [
            'section.required' => '⚠️ Please select a section',
            'name.required' => '⚠️ Please enter a task name between 3 and 255 characters long.'
        ]);

        $task->section = $request->section;
        $task->name = $request->name;
        $task->complete = $request->has('complete');
        $task->save();

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }
}
