<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\AchievementRequest;
use App\Http\Requests\AchievementSearchRequest;
use App\TournamentUser;
use App\User;
use App\UserAchievement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Achievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AchievementsController extends Controller
{

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    ini_set('memory_limit', '2000M');

    $achievements = achievement::orderBy('name', 'asc')->paginate(10);
    return view('admin.achievements.achievements')->with('achievements', $achievements);
  }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function addAchievementIndex()
  {
    return view('admin.achievements.addachievements');
  }

  /**
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * returns table with all achievements
   */
  public function checkAchievement($id)
  {
    $achievements = achievement::where('id', $id)->first();
    return view('admin.achievements.checkachievements', ['achievements' => $achievements]);
  }

  /**
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * deletes achievement
   */
  public function deleteAchievement($id)
  {
    //sets memory_limit for images to 2000mb
    ini_set('memory_limit', '2000M');

    //delete achievement where id = $id
    achievement::where('id', $id)->delete();

    $newDataAchievement = achievement::paginate(10);
    $succes[] = "image with an id of " . $id . " has been removed";
    return view('admin.achievements.achievements', ['succes' => $succes, 'achievements' => $newDataAchievement]);
  }

  /**
   * @param AchievementRequest $req
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * adds achievements
   */
  public function postAddAchievement(AchievementRequest $req)
  {
    ini_set('memory_limit', '200M');

    // Returns a base64 encoded image,Error or placeholder image.

    $handleImage = $this->handleProductImageUpload($post = "post", $req, $controllername = 'achievements');
    //imagehandler/image error handler
    if ($handleImage !== false) {

      $image = $handleImage;

      //adds new achievement in database
      $data = New achievement;
      $data->name = $req->input('name');
      $data->description = $req->input('description');
      $data->condition = $req->input('condition');
      $data->requirement = $req->input('requirement');
      $data->image = $image;
      $data->save();

      $achievement = achievement::paginate(10);
      $req->session()->flash('success', 'Achievement has been added');

      return view('admin.achievements.addachievements', ['achievements' => $achievement]);
    }
  }

  /**
   * @param Request $req
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * updates achievement
   */
  public function postUpdateAchievement(AchievementRequest $req, $id)
  {
    ini_set('memory_limit', '200M');
    $oldDataAchievement = Achievement::all();

    // Returns a base64 encoded image, or placeholder image
    $handleImage = $this->handleProductImageUpload($method = "update", $req, $controllername = 'achievements');

    if ($handleImage !== false && $handleImage != 'noimage') {
      $image = $handleImage;

      //updates image with image $id
      $update = Achievement::find($id);
      $update->name = $req['name'];
      $update->description = $req['description'];
      $update->condition = $req->input('condition');
      $update->requirement = $req->input('requirement');
      $update->image = $image;
      $update->save();

      $newDataAchievement = Achievement::paginate(10);
      $succes[] = "Achievement with an id of " . $id . " has been updated";
      return view('admin.achievements.achievements', ['succes' => $succes, 'achievements' => $newDataAchievement]);

    } else if ($handleImage == 'noimage') {
      $update = Achievement::find($id);
      $update->name = $req['name'];
      $update->description = $req['description'];
      $update->condition = $req->input('condition');
      $update->requirement = $req->input('requirement');
      $update->save();

      $newDataAchievement = Achievement::paginate(10);
      $succes[] = "Achievement with an id of " . $id . " has been updated";

      return view('admin.achievements.achievements', ['achievements' => $newDataAchievement]);
    } else {
      return view('admin.achievements.achievements', ['achievements' => $oldDataAchievement]);
    }
  }

  public function checkConditionRequirement($tournamentid)
  {
    // function should be used when the table is updated for user
    $allachieve = Achievement::all();
    foreach (TournamentUser::where('tournament_id' , $tournamentid)->get() as $user) {
       
        foreach ($allachieve as $value) {
            if ($value['condition'] == 'win') {
                //checks condition
                if (count(TournamentUser::where('user_id', $user->user_id)->where('win', 1)->get()) >= $value['requirement']) {
                    //counts amount of rows with wins in them
                    $this->getAchievement($value['id'],$user->user_id);
                }
            }
            if ($value['condition'] == 'lose') {//checks condition

                if (count(TournamentUser::where('user_id', $user->user_id)->where('win', 0)->get()) >= $value['requirement']) {
                    //counts the amount of rows with loss in them
                    $this->getAchievement($value['id'],$user->user_id);
                }
            }
            if ($value['condition'] == 'score') { //checks condition
                if (TournamentUser::where('user_id', $user->user_id)->orderBy('score', 'desc')->first()->score >= $value['requirement']) {
                    // ifstatement checks the highest score if its more then the requirement or same as
                    $this->getAchievement($value['id'],$user->user_id);//gives achievement

                }
            }
            if ($value['condition'] == 'winstreak') {//checks condition
                $i = 1;
                foreach (TournamentUser::where('user_id', $user->user_id)->orderBy('updated_at', 'desc')->pluck('win') as $win) {
                    if ($win == 0) { //if the win is 0 the user has lost to recently
                        break 1;// breaks out of foreach loop
                    }
                    if ($i >= $value['requirement']) { // checks if cycle is more or same as requirement
                        $this->getAchievement($value['id'],$user->user_id);
                        break 1;// breaks out of foreach loop
                    }
                    $i++;// adds 1 to the cycle so howmany wins in a row
                }
            }
            if ($value['condition'] == 'losestreak') {
                $i = 1;
                foreach (TournamentUser::where('user_id', $user->user_id)->orderBy('updated_at', 'desc')->pluck('win') as $win) {
                    if ($win == 1) { // has resently won so is not on a losingstreak
                        break 1;//breaks out of foreach
                    }
                    if ($i >= $value['requirement']) { // checks if cycle is more or same as requirement
                        $this->getAchievement($value['id'],$user->user_id); //gives achievement
                        break 1; //breaks out of foreach
                    }
                    $i++; // adds 1 to cycle
                }
            }
        }

    }
  }

  /**
   * @param $achievid
   * creates achievement
   */
  public function getAchievement($achievid,$user)
  {
      try {
          if (count(UserAchievement::where('achievement_id', $achievid)->where('user_id', $user)->get()) < 1) {
              $achievement = new UserAchievement();
              $achievement->user_id = $user;
              $achievement->achievement_id = $achievid;
              $achievement->save();
          }
      } catch (\PDOException $e) {
          echo $e;
      }

  }

  /**
   * @param AchievementSearchRequest $request
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function searchAchievements(AchievementSearchRequest $request)
  {
    foreach ($request['achievements'] as $achievement_id) {
      $achievement_idArray[] = $achievement_id;
    }
    $achievements = achievement::whereIn('id', $achievement_idArray)->orderby('name', 'asc')->paginate(1000);
    return view('admin.achievements.achievements', compact('achievements'));
  }

}
