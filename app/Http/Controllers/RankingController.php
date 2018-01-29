<?php

namespace App\Http\Controllers;

use Illuminate\Http\Requests;
use App\Http\Requests\RankingSearchUserRequest;
use App\Http\Requests\RankingSearchBoardgameRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\User;
use App\TournamentUser;
use App\Boardgame;
use Auth;

class RankingController extends Controller
{
    /**
     * Show main ranking
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $rankings = User::orderBy('wins', 'DESC')->paginate(10);

        return view('rankings.rankings', compact('rankings'));
    }

    /**
     * Show the ranking in lossing order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rankingsbylosses(){
        $rankingsbylosses = User::orderBy('losses', 'DESC')->paginate(10);

        return view('rankings.rankingsbylosses', compact('rankingsbylosses'));
    }

    /**
     * Show the rank info of a given user
     * @param RankingSearchUserRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchUsers(RankingSearchUserRequest $request){
        foreach($request['users'] as $user_id){
            $user_idArray[] = $user_id;
        }
        $rankings = User::whereIn('id', $user_idArray)->get();
        $rankings = $rankings->sortByDesc('wins');

        return view('rankings.searchuserrankings', compact('rankings'));
    }

    /**
     * Show to top ranking users of an given boardgame
     * @param RankingSearchBoardgameRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function searchBoardgamepost(RankingSearchBoardgameRequest $request){
        $boardgameid = $request['game'];

        return redirect()->route('searchBoardgameRankingsget', [$boardgameid]);
    }

    /**
     * Show to top 50 of a given boardgame
     * @param $boardgameid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchBoardgameget($boardgameid){

        $rankings = User::paginate(50);
        foreach($rankings as $ranking){
            $ranking->setBoardNumber($boardgameid);
        }

        $rankings = $rankings->sortByDesc('tournament_wins');

        $boardgameNames = Boardgame::where('id', $boardgameid)->first();
        $boardgameName = $boardgameNames['name'];

        return view('rankings.boardgamerankings' , compact("boardNumber", "rankings", 'boardgameName'));
    }
}