<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BoardOfDirector;
use App\Models\Category;
use App\Models\Machine;
use App\Models\News;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Setting;

class HomePageController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();
        $firstThreeSectors = Sector::take(3)->get();
        $nextFourSectors = Sector::get();
        $lastnew = News::where('frontpage', 1)->first();
        $projectCount = Project::count();
        $sectorCount = Sector::count();
        $machineCount = Machine::sum('quantity');
        $projeCategoryCount = Category::count();
        $boardMembers = BoardOfDirector::orderBy('order')->get();

        // Yeni Eklenen: Çalışan Sayısı ve Güncellenme Tarihi
        $personnelSetting = Setting::where('key', 'personnel_count')->first();
        $personnelCount = $personnelSetting?->value ?? 1;
        $personnelUpdatedAt = $personnelSetting?->updated_at;

        return view('front.index', compact(
            'news',
            'firstThreeSectors',
            'nextFourSectors',
            'lastnew',
            'projectCount',
            'sectorCount',
            'machineCount',
            'projeCategoryCount',
            'boardMembers',
            'personnelCount',
            'personnelUpdatedAt'
        ));
    }

}
