<?php

namespace App\Http\Controllers;

use App\Achievement;
use App\UserAchievement;
use Illuminate\Http\Request;
use App\User;
use App\Boardgame;
use App\favorite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Auth;



/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
  /**
   * @param User $user
   * @param $favorite = get all data of user id.
   * @param $boardgame = data of boardgame.
   * foreach: boardgame id that is linked to the user id.
   * Go get result and out that in an array $boardgame.
   * @return View
   */
  public function show(User $user)
  {
//    searching for people
    if(request('search') != null){
      return redirect()->route("profileView", ['user' => (int)request('search')]);
    }



    $achievement =  UserAchievement::where('user_id', $user->id)->get();
    $favorite = favorite::where('user_id', $user->id)->get();




        foreach($favorite as $key){
            $boardgame[] = boardgame::where('id', $key->boardgame_id)->get();
        }

        if(!empty($achievement)) {
            foreach($achievement as $achievements){
                $achievementUser[] = Achievement::where('id', $achievements->achievement_id)->get();
            }
        }
      return view('user.profileView', compact('user', 'boardgame', 'achievementUser'));
    }

  public function edit(User $user)
  {
    if ($user->id != Auth::id()) {
      return redirect()->back();
    }
    return view('user.profileEdit', compact('user'));

  }

  /**
   * @param Request $request
   * @return View
   */
  public function save(Request $request)
  {
    ini_set('memory_limit', '200M');
    $handleImage = $this->handleProductImageUpload($method = "update", $request, $controllername = 'users');



//    get user data from database.
    $user = User::find($_POST['id']);
//    Checks if data has changed between database and post. If true check if requirements are met.
//     Otherwise return back to page.
    if ($_POST['username'] != $user->getOriginal('username')) {
      $validator = Validator::make($request->all(), ['username' => 'required|unique:users|max:99']);
      if ($validator->fails()):
        $request->session()->flash('error', 'Er is iets fout gegaan tijdens het veranderen van uw Gebruikersnaam.');
        return redirect()->back();
      endif;
    }
    if ($_POST['email'] != $user->getOriginal('email')) {
      $validator = Validator::make($request->all(), ['email' => 'required|email|unique:users|max:255']);
      if ($validator->fails()):
        $request->session()->flash('error', 'Er is iets fout gegaan tijdens het veranderen van uw E-mail.');
        return redirect()->back();
      endif;
    }
//    Checks if posted data mets the requirements from the validator. Returns to previous page if data is not correct.
    $validator = Validator::make($request->all(), [
      'firstname' => 'required|max:255',
      'lastname' => 'required'
    ]);
    if ($validator->fails()):
      $request->session()->flash('error', 'Er is iets fout gegaan tijdens het veranderen van uw voor/achternaam.');
      return redirect()->back();
    endif;
//    replaces the post data with the current data and then saves it.
    if ($handleImage !== false && $handleImage != 'noimage'){
      $image = $handleImage;
//      precho($user);
//      die;
      $user->fill([
        'firstname' => $_POST['firstname'],
        'insertion' => $_POST['insertion'],
        'lastname'  => $_POST['lastname'],
        'city'      => $_POST['city'],
        'username'  => $_POST['username'],
        'email'     => $_POST['email'],
        'image'     => $image
      ]);
    }
    else if ($handleImage == 'noimage'){
      $user->fill(['firstname'  => $_POST['firstname'],
        'insertion'             => $_POST['insertion'],
        'lastname'              => $_POST['lastname'],
        'city'                  => $_POST['city'],
        'username'              => $_POST['username'],
        'email'                 => $_POST['email']]);
    }

    $user->save();
    return redirect()->route('profileView', ['user'=>$user->id]);
  }
}
