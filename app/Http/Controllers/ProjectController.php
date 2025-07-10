<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //no available projects
        if(Auth::user()->projects->isEmpty()){
            return response()->json(['message' => 'No projects available'], 404);
        }
        return Auth::user()->projects;
    }

    /**
     * Show the form for creating a new resource.
     */
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=> 'required|string|max:255',
            'description'=> 'nullable|string',
        ]);
        $project = Auth::user()->projects()->create($data);
        return response()->json($project, 201);
    
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->autherizeProject($project);
        return $project;
    }

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->autherizeProject($project);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $project->update($data);
        return response()->json($project, 200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->autherizeProject($project);
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully'], 200);
    }


    public function autherizeProject(Project $project)
    {
       if ($project->user_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }
    }
}
