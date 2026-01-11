<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return ProjectResource::collection(Project::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tech_stack' => 'nullable',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads/projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('uploads/projects/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        if ($request->has('tech_stack') && !is_array($request->tech_stack)) {
            $validated['tech_stack'] = json_decode($request->tech_stack, true);
        }

        $project = Project::create($validated);
        
        ActivityLog::log('إضافة', 'مشروع', "قام المدير بإضافة مشروع جديد: {$project->title}");

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tech_stack' => 'nullable',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('uploads/projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            // Option: delete old gallery or append? Usually replace is easier for MVP
            if ($project->gallery) {
                foreach ($project->gallery as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('uploads/projects/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        if ($request->has('tech_stack') && !is_array($request->tech_stack)) {
            $validated['tech_stack'] = json_decode($request->tech_stack, true);
        }

        $project->update($validated);

        ActivityLog::log('تعديل', 'مشروع', "قام المدير بتعديل المشروع: {$project->title}");

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $title = $project->title;
        
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->gallery) {
            foreach ($project->gallery as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $project->delete();

        ActivityLog::log('حذف', 'مشروع', "قام المدير بحذف المشروع: {$title}");

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
