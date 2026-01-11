<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VolunteerRequest;
use Illuminate\Http\Request;
use App\Http\Resources\VolunteerResource;

class VolunteerController extends Controller
{
    public function index()
    {
        return VolunteerResource::collection(VolunteerRequest::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'cv_path' => 'nullable|string',
        ]);

        $volunteer = VolunteerRequest::create($validated);
        return new VolunteerResource($volunteer);
    }

    public function update(Request $request, VolunteerRequest $volunteer)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,accepted,rejected',
        ]);

        $volunteer->update($validated);
        return new VolunteerResource($volunteer);
    }

    public function destroy(VolunteerRequest $volunteer)
    {
        $volunteer->delete();
        return response()->json(['message' => 'Volunteer request deleted successfully']);
    }
}
