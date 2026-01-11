<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\News;
use App\Models\Message;
use App\Models\VolunteerRequest;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        // Weekly Activity for Charts (Simplified for MVP)
        $chartData = [
            ['name' => 'السبت', 'visits' => rand(100, 500), 'projects' => Project::count()],
            ['name' => 'الأحد', 'visits' => rand(100, 500), 'projects' => Project::count()],
            ['name' => 'الاثنين', 'visits' => rand(100, 500), 'projects' => Project::count()],
            ['name' => 'الثلاثاء', 'visits' => rand(100, 500), 'projects' => Project::count()],
            ['name' => 'الأربعاء', 'visits' => rand(100, 500), 'projects' => Project::count()],
            ['name' => 'الخميس', 'visits' => rand(100, 500), 'projects' => Project::count()],
            ['name' => 'الجمعة', 'visits' => rand(100, 500), 'projects' => Project::count()],
        ];

        return response()->json([
            'projects' => Project::count(),
            'news' => News::count(),
            'messages' => Message::count(),
            'volunteers' => VolunteerRequest::count(),
            'team_members' => TeamMember::count(),
            'chartData' => $chartData
        ]);
    }
}
