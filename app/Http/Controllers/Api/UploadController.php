<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Handle high-quality production image uploads.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,ico|max:5120', // Increased to 5MB for high-quality
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Generate a clean filename
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store in storage/app/public/uploads
            $path = $file->storeAs('uploads', $filename, 'public');
            
            // Return full URL
            $url = asset('storage/' . $path);

            return response()->json([
                'status' => 'success',
                'url' => $url,
                'path' => $path,
                'message' => 'Image uploaded successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No file uploaded'
        ], 400);
    }
}
