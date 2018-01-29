<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestionRequest;
use App\Suggestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Class suggestionController
 * @package App\Http\Controllers
 */
class suggestionController extends Controller
{
    /**
     * Return the view for creating a new suggestion
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('suggestion.create');
    }

    /**
     * Takes the posted form
     * Prepares the data
     * Stores the data in the database
     * Loads the home route and adds a confirmation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(SuggestionRequest $request)
    {
        $boardgame_name = strip_tags($request['boardgame_name']);
        $description = strip_tags($request['description']);
        $user_id = Auth::user()->id;

        Suggestion::create(['boardgame_name' => $boardgame_name, 'description' => $description, 'user_id' => $user_id]);
        session()->flash('success', 'Bedankt voor de suggestie, we gaan ernaar kijken!');

        return redirect()->route('home');
    }
}