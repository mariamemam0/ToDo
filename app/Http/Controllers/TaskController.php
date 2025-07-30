<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $this->autherizeProject($project);

        return response()->json($project->tasks);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
                $this->autherizeProject($project);

                $data = $request->validate([
            'title' => 'required|string|max:255',
            'is_completed' => 'boolean',
            'due_date' => 'nullable|date',
                ]);

                $task = $project->tasks()->create($data);
                return response()->json($task, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project,Task $task)
    {
        $this->autherizeProject($project);
        $this->autherizeTask($project, $task);

        return response()->json($task);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Project $project , Task $task )
    {
        $this->autherizeProject($project);
        $this->autherizeTask($project, $task);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'is_completed' => 'boolean',
            'due_date' => 'nullable|date',
        ]);

        $task->update($data);
        return response()->json($task);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project,Task $task)
    {
        $this->autherizeProject($project);
        $this->autherizeTask($project, $task);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    private function autherizeProject(Project $project)
    {
        if($project->user_id !== auth()->id()){
            abort(403, 'Unauthorized action.');
        }
    }

    private function autherizeTask(Project $project, Task $task)
    {
        if($task->project_id !== $project->id){
            abort(403, 'Unauthorized action.');
        }
    }
}
