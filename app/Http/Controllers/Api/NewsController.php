<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Resources\NewsResource;

class NewsController extends Controller
{
    public function index()
    {
        return NewsResource::collection(News::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('uploads/news', 'public');
        }

        $news = News::create($validated);
        
        ActivityLog::log('إضافة', 'خبر', "قام المدير بإضافة خبر جديد بعنوان: {$news->title}");

        return new NewsResource($news);
    }

    public function show(News $news)
    {
        return new NewsResource($news);
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($news->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($news->image);
            }
            $validated['image'] = $request->file('image')->store('uploads/news', 'public');
        }

        $news->update($validated);

        ActivityLog::log('تعديل', 'خبر', "قام المدير بتعديل الخبر: {$news->title}");

        return new NewsResource($news);
    }

    public function destroy(News $news)
    {
        $title = $news->title;
        $news->delete();

        ActivityLog::log('حذف', 'خبر', "قام المدير بحذف الخبر: {$title}");

        return response()->json(['message' => 'News deleted successfully']);
    }
}
