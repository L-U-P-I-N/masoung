<?php
// app/Http/Controllers/PublicController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    private function settings()
    {
        return DB::table('tribe_settings')->first();
    }

    public function home()
    {
        $settings = $this->settings();
        
        // Fetch items for the banner (latest 2 news + latest 2 activities)
        $newsForSlider = DB::table('news')->where('is_published', true)->orderBy('published_at', 'desc')->limit(2)->get();
        $actsForSlider = DB::table('activities')->where('is_published', true)->orderBy('activity_date', 'desc')->limit(2)->get();
        
        $sliderItems = collect();
        foreach($newsForSlider as $n) {
            $n->slider_type = 'news';
            $n->slider_url  = route('news.show', $n->id);
            $n->slider_label = 'خبر جديد';
            $sliderItems->push($n);
        }
        foreach($actsForSlider as $a) {
            $a->slider_type = 'activity';
            $a->slider_url  = route('activities.show', $a->id);
            $a->slider_label = 'نشاط قادم';
            $sliderItems->push($a);
        }
        // Group by creation/publish date to keep them sorted altogether if needed, 
        // but user requested "latest 2 and 2", so we just have them in the collection.

        $latestNews = DB::table('news')->where('is_published', true)->orderBy('published_at', 'desc')->limit(3)->get();
        $latestActs = DB::table('activities')->where('is_published', true)->orderBy('activity_date', 'desc')->limit(3)->get();
        
        $membersCount    = DB::table('members')->where('is_active', true)->count();
        $newsCount       = DB::table('news')->where('is_published', true)->count();
        $activitiesCount = DB::table('activities')->where('is_published', true)->count();

        return view('public.home', compact('settings', 'latestNews', 'latestActs', 'membersCount', 'newsCount', 'activitiesCount', 'sliderItems'));
    }

    public function about()
    {
        $settings = $this->settings();
        return view('public.about', compact('settings'));
    }

    public function members(Request $request)
    {
        $settings = $this->settings();
        $query = $request->get('search');
        
        $members = DB::table('members')
            ->where('is_active', true)
            ->when($query, function($queryBuilder, $search) {
                return $queryBuilder->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('phone', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('sort_order')
            ->get();
            
        return view('public.members', compact('settings', 'members', 'query'));
    }

    public function memberShow($id)
    {
        $settings = $this->settings();
        $member   = DB::table('members')->where('id', $id)->where('is_active', true)->first();
        
        if (!$member) {
            abort(404);
        }
        
        return view('public.member_show', compact('settings', 'member'));
    }

    public function activities()
    {
        $settings    = $this->settings();
        $activities  = DB::table('activities')->where('is_published', true)->orderBy('activity_date', 'desc')->paginate(9);
        return view('public.activities', compact('settings', 'activities'));
    }

    public function activityShow($id)
    {
        $settings  = $this->settings();
        $activity  = DB::table('activities')->where('id', $id)->where('is_published', true)->first();
        
        if (!$activity) {
            abort(404);
        }
        
        return view('public.activity_show', compact('settings', 'activity'));
    }

    public function news()
    {
        $settings = $this->settings();
        $news     = DB::table('news')->where('is_published', true)->orderBy('published_at', 'desc')->paginate(9);
        return view('public.news', compact('settings', 'news'));
    }

    public function newsShow($id)
    {
        $settings = $this->settings();
        $item     = DB::table('news')->where('id', $id)->where('is_published', true)->first();
        
        if (!$item) {
            abort(404);
        }
        
        return view('public.news_show', compact('settings', 'item'));
    }
}
