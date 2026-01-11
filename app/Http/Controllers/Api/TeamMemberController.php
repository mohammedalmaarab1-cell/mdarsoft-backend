<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use App\Http\Resources\TeamMemberResource;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        return TeamMemberResource::collection(TeamMember::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'skills' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/team', 'public');
            $validated['image'] = $path;
        }

        if ($request->has('skills') && !is_array($request->skills)) {
            $validated['skills'] = json_decode($request->skills, true);
        }

        $member = TeamMember::create($validated);
        return new TeamMemberResource($member);
    }

    public function show(TeamMember $teamMember)
    {
        return new TeamMemberResource($teamMember);
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'role' => 'string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'skills' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($teamMember->image) {
                Storage::disk('public')->delete($teamMember->image);
            }
            $path = $request->file('image')->store('uploads/team', 'public');
            $validated['image'] = $path;
        }

        if ($request->has('skills') && !is_array($request->skills)) {
            $validated['skills'] = json_decode($request->skills, true);
        }

        $teamMember->update($validated);
        return new TeamMemberResource($teamMember);
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->image) {
            Storage::disk('public')->delete($teamMember->image);
        }
        $teamMember->delete();
        return response()->json(['message' => 'Team member deleted successfully']);
    }
}
