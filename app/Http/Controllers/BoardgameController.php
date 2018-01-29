<?php

namespace App\Http\Controllers;

use App\Boardgame;
use App\favorite;
use App\BoardgameRating;
use Illuminate\Http\Request;
use Auth;

/**
 * Class BoardgameController
 * @package App\Http\Controllers
 */
class BoardgameController extends Controller
{
    /**
     * Show a boardgame using a given id
     * @param Boardgame $boardgame
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Boardgame $boardgame)
    {
        $rating = Boardgame::getRating($boardgame->id);

        if (Auth::check()) {
            $userVote = BoardgameRating::where('boardgame_id', $boardgame->id)->where('user_id', Auth::user()->id)->get();
            $favorite = favorite::where('boardgame_id', $boardgame->id)->where('user_id', Auth::user()->id)->get();
        } else {
            $userVote = [];
            $favorite = [];
        }

        return view('boardgame', ['game' => $boardgame, 'rating' => $rating, 'userVote' => $userVote, 'favorite' => $favorite]);
    }

    /**
     * Vote for a boardgame
     * @param $id
     * @param int $value
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function vote($id, $value = 0)
    {
        // make sure it's a 0 or a 1
        $value = ($value == 0 ? 0 : 1);

        // Check if game exists and if user has already voted
        if (count(Boardgame::where('id', $id)->get()) != 1) {
            return back();
        }

        if (count(BoardgameRating::where('boardgame_id', $id)->where('user_id', Auth::user()->id)->get()) == 1) {
            //update
            BoardgameRating::where('boardgame_id', $id)->where('user_id', Auth::user()->id)->update(['vote' => $value]);
        } else {
            // add
            BoardgameRating::create([
                'user_id' => Auth::user()->id,
                'boardgame_id' => $id,
                'vote' => $value,
            ]);
        }

        // return back
        return redirect()->route('boardgame', ['id' => $id]);
    }

    /**
     * checks in the database if there is already a link between the user and boardgame. If true return with error message.
     * Otherwise create a new link with the users id and boardgame id.
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFavorite($id)
    {
        if (count(favorite::where('boardgame_id', $id)->where('user_id', Auth::user()->id)->get()) == 1) {
            session()->flash('success', 'SOMETHING WENT WRONG');
            return back();
        }
        session()->flash('success', 'dit bordspel staat nu tussen uw favorieten en u staat in zijn favorieten.   ;)');
        favorite::create([
            'user_id' => Auth::user()->id,
            'boardgame_id' => $id]);
        return redirect()->route('boardgame', ['id' => $id]);
    }

    /**
     * checks if there is a link between the user and boardgame.
     * If true it will delete the link and returns a message that the board game is deleted.
     * If false then it will return witha an error meassage.
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delFavorite($id)
    {
        if (count(favorite::where('boardgame_id', $id)->where('user_id', Auth::user()->id)->get()) == 1) {
            favorite::where('boardgame_id', $id)->where('user_id', Auth::user()->id)->delete();
            session()->flash('error', 'Dit bordspel is gekwestst nu hij niet meer tussen uw favorieten staat.  :(');
            return redirect()->route('boardgame', ['id' => $id]);
        } else {
            session()->flash('error', 'SOMETHING WENT WRONG');
            return redirect()->route('boardgame', ['id' => $id]);
        }
    }

}
