<?php

namespace App\Http\Controllers\admin;

use App\Suggestion;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Class SuggestionController
 * @package App\Http\Controllers\admin
 */
class SuggestionController extends Controller
{
  /**
   * Reads out all suggestions
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function read()
  {
    return view('admin.suggestion.read', ['data' => Suggestion::all()]);// sends all info needed for view
  }

  /**
   * Deletes the suggestion and displays a confirmation
   * @param $id
   * @param $user_id
   * @param $name
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function delete($id)
  {
    $suggestion = Suggestion::find($id); // deletes suggestion
    session()->flash('success', "De suggestie '".$suggestion->boardgame_name."' van gebruiker ".$suggestion->user->username." is succesvol verwijderd!");
    $suggestion->delete();
    return redirect()->route('suggestionHandle');
  }

  /**
   * Redirects to boardgame creation page
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function accept($id)
  {// accepts suggestion and redirects to the boardgame add page to add suggestion
      $suggestion = Suggestion::find($id);
      return view('admin.boardgame.create', ['suggestion' => $suggestion]);
  }
}
