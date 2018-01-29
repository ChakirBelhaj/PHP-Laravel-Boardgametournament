<?php

namespace App\Http\Controllers\admin;

use App\Boardgame;
use App\Http\Requests\BoardgameRequest;
use App\Http\Requests\RankingSearchBoardgameRequest;
use App\Http\Controllers\Controller;
use App\Suggestion;
use Illuminate\Http\Request;

/**
 * Class BoardgameController
 * @package App\Http\Controllers\admin
 */
class BoardgameController extends Controller
{
    /**
     * Show all the boardgames
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $boardgames = Boardgame::orderBy('name', 'asc')->paginate(10);
        return view('admin.boardgame.index', ['boardgames' => $boardgames]);
    }

    /**
     * Show create beardgame form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()// loads view boardgames
    {
        return view('admin.boardgame.create');
    }

    /**
     * Save given data as an new boardgame
     * @param BoardgameRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(BoardgameRequest $request)
    {
        if(!empty($request['suggestion_id'])){ //
            $suggestion = Suggestion::find($request['suggestion_id']);
            $suggestion->delete();
        }
        // all below is new database row creates
        try {
            $boardgame = new Boardgame;
            $boardgame->name = $request['name'];
            $boardgame->image = $request['img'];
            $boardgame->minplayers = $request['minplayers'];
            $boardgame->maxplayers = $request['maxplayers'];
            $boardgame->playtime = $request['playtime'];
            $boardgame->yearpublished = $request['yearpublished'];
            $boardgame->isexpansion = ($request['isexpansion'] == NULL) ? '0' : '1';
            $boardgame->save();
            $request->session()->flash('success', 'Het bordspel is opgeslagen!'); //flashmessage is added when the update is done
        } catch (\PDOException $e) {
            $request->session()->flash('error', 'Er is iets fout gegaan.');
        }

        return redirect()->route('AdminBoardgame');
    }

    /**
     * Show edit boardgame form
     * @param Boardgame $boardgame
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Boardgame $boardgame)
    {
        return view('admin.boardgame.edit', ['editgame' => $boardgame]);
    }

    /**
     * Store the update info of a given boardgame
     * @param Request $request
     * @param BoardgameRequest $Brequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, BoardgameRequest $Brequest)
    {
        try {
            $update = Boardgame::find($request->input('hiddenid')); // updates existing rows
            $update->name = $Brequest['name'];
            $update->image = $request['img'];
            $update->minplayers = $Brequest['minplayers'];
            $update->maxplayers = $Brequest['maxplayers'];
            $update->playtime = $Brequest['playtime'];
            $update->yearpublished = $Brequest['yearpublished'];
            $update->isexpansion = $Brequest['isexpansion'];
            $update->save();
            $request->session()->flash('success', 'Het bordspel is upgedate');

        } catch (\PDOException $e) {
            $request->session()->flash('error' , 'Er is iets fout gegaan');
        }
        return redirect()->route('AdminBoardgame');
    }

    /**
     * Delete a given boardgame
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        Boardgame::find($request->input('hiddenid'))->delete();//deletes boardgame with id

        return redirect()->route('AdminBoardgame');
    }

  /**
   * @param RankingSearchBoardgameRequest $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
    public function searchBoardgame(RankingSearchBoardgameRequest $request){
        foreach($request['game'] as $boardgame_id){
            $boardgame_idArray[] = $boardgame_id;
        }
        $boardgames = Boardgame::whereIn('id', $boardgame_idArray)->orderby('name', 'asc')->paginate(10);
        return view('admin.boardgame.index', compact('boardgames'));
    }
}
