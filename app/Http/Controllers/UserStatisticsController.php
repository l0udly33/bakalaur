<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserStatisticsController extends Controller
{
    public function index()
    {
        return view('statistics.index');
    }

    public function fetchStats(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'tag' => 'required|string',
        ]);

        $riotID = rawurlencode($request->username . '#' . $request->tag);

        $trackerUrl = "https://tracker.gg/valorant/profile/riot/{$riotID}/overview?platform=pc&playlist=competitive&season=16118998-4705-5813-86dd-0292a2439d90";

        return view('statistics.index', [
            'username' => $request->username,
            'tag' => $request->tag,
            'trackerUrl' => $trackerUrl
        ]);
    }
}
