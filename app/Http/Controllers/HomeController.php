<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\favorite;
use App\Boardgame;
use App\Tournament;
use App\BoardgameRating;
use Auth;
use DB;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $likes = BoardgameRating::select('boardgame_id')->whereNotNull('vote')->selectRaw('COUNT(boardgame_id) AS result')->groupby('boardgame_id')->orderBy('result', 'DESC')->get();
        
        $tournaments = Tournament::orderBy('status_id', 'startdate', 'maxplayers')->where('status_id', '=', '1')->paginate(10);
        if (!empty(Auth::id())) {
            $userId = $userId = Auth::id();
            $favorite = favorite::where('user_id', $userId)->get();
            foreach ($favorite as $key) {
                $boardgame[] = boardgame::where('id', $key->boardgame_id)->get();
            }
        }

        $ranking = User::orderBy('wins', 'DESC')->paginate(10);
        return view('home', compact('ranking', 'boardgame', 'tournaments', 'likes', 'likesName'));
    }
}
