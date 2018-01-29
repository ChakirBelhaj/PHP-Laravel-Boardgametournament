<?php

namespace App\Http\Controllers;

use App\Boardgame;
use App\BoardgameRating;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * Show all boardgames with there rating
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        switch (Input::get('search_type')) {
            case 'name':
                $games = Boardgame::where('name', 'LIKE', '%' . Input::get('search_string') . '%')->orderBy('name', 'asc');
                break;
            case 'minp':
                $games = Boardgame::where('minplayers', '>=', Input::get('search_string'))->orderBy('minplayers', 'asc')->orderBy('name', 'asc');
                break;
            case 'maxp':
                $games = Boardgame::where('maxplayers', '<=', Input::get('search_string'))->orderBy('maxplayers', 'desc')->orderBy('name', 'asc');
                break;
            case 'time':
                $games = Boardgame::where('playtime', '<=', Input::get('search_string'))->orderBy('playtime', 'desc')->orderBy('name', 'asc');
                break;
            default:
                $games = Boardgame::orderBy('name', 'asc');
        }

        if(empty(Input::get('search_string'))) {
            $games = Boardgame::orderBy('name', 'asc');
        }

        if((Input::get('search_type') == 'minp' || Input::get('search_type') == 'maxp' || Input::get('search_type') == 'time') && !is_numeric(Input::get('search_string'))){
            $games = Boardgame::orderBy('name', 'asc');
            session()->flash('error', 'Er is geen nummer ingevoerd');
        }

        $games = $games->paginate(25)->appends(Input::only('search_type', 'search_string'));

        foreach($games as $key => $value){
            $games[$key]['rating'] = (count(BoardgameRating::where('boardgame_id', $value['id'])->get()) >= 5 ? Boardgame::getRating($value['id']) : false);
        }

        return view('search', ['games' => $games]);
    }
}
