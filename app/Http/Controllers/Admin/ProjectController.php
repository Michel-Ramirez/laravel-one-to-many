<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderBy('updated_at', 'DESC')->get();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $project = new Project;
        return view('admin.projects.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // dd($data);
        $request->validate([

            'title' => ['required', 'string', Rule::unique('projects')],
            'description' => 'required|string',
            'image' => 'nullable|image',

        ], [
            'title.required' => 'Questo campo è obbligatorio',
            'title.unique' => 'Questo progetto esiste già',
            'description.required' => 'Aggiungi una descrizione del progetto',
            'image.image' => 'Il file caricato non è valido'
        ]);

        $project = new Project();

        if (array_key_exists('image', $data)) {
            $img_url = Storage::put('project_images', $data['image']);
            $data['image'] = $img_url;
        }

        $project->slug = Str::slug($data['title'], '-');

        $project->fill($data);

        $project->save();

        return to_route('admin.projects.show', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([

            'title' => ['required', 'string', Rule::unique('projects')->ignore($project->id)],
            'description' => 'required|string',
            'image' => 'nullable|image',

        ], [
            'title.required' => 'Questo campo è obbligatorio',
            'title.unique' => 'Questo progetto esiste già',
            'description.required' => 'Aggiungi una descrizione del progetto',
            'image.image' => 'File caricato non valido'
        ]);

        $data = $request->all();
        $project->update();

        return to_route('admin.projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index');
    }
}
