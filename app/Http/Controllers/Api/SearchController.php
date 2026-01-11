<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\News;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json(['data' => []]);
        }

        $projects = Project::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                $item->type = 'project';
                $item->url = '/projects';
                return $item;
            });

        $services = Service::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                $item->type = 'service';
                $item->url = '/services';
                return $item;
            });

        $news = News::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->get()
            ->map(function($item) {
                $item->type = 'news';
                $item->url = '/news';
                return $item;
            });

        $results = $projects->concat($services)->concat($news);

        return response()->json(['data' => $results]);
    }
}
