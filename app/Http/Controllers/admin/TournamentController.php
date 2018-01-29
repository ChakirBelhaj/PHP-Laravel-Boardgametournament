<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\InviteRequest;
use App\Tournament;
use App\TournamentUser;
Use App\TournamentRegistration;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DB;

class TournamentController extends Controller
{
    /**
     * sends tournements of user to view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $tournaments = Auth::user()->tournamentsOwner;

        return view('tournament.admin.index', compact('tournaments'));
    }

    /**
     * loads view edit
     * @param Tournament $tournament
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Tournament $tournament)
    {
        return view('tournament.admin.edit', compact('tournament'));
    }

    /**
     * updates tournament is edited
     * @param Tournament $tournament
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Tournament $tournament)
    {
        $val = Validator::make(request()->toArray(), [ // validates all incoming post data
            'rounds' => 'required|numeric',
            'spelers' => 'required|regex:([0-9]{1,3}\s?-\s?[0-9]{1,3})',
            'startdate' => 'required|date',
            'enddate' => 'nullable|date',
            'streetname' => 'nullable|string',
            'housenumber' => 'nullable|numeric',
            'zipcode' => 'nullable|string',
            'city' => 'nullable|string',
            'game' => 'required|exists:boardgames,id',
            'description' => '',
        ]);

        if ($val->fails()):
            session()->flash('error', 'De data is niet correct!');
            return redirect()->back(); // if data wrong redirect back
        endif;

        if(request('rounds') < $tournament->tournementUser->max('round')){
            session()->flash('error', 'Je kunt niet minder rondes hebben dan dat je al gestart hebt');
            return redirect()->back();
        }

        $players = str_replace(' ', '', request('spelers'));
        $minplayers = explode('-', $players)[0];
        $maxplayers = explode('-', $players)[1];

        $tournament->fill([
            'rounds' => request('rounds'),
            'minplayers' => $minplayers,
            'maxplayers' => $maxplayers,
            'startdate' => request('startdate'),
            'enddate' => request('enddate'),
            'streetname' => request('streetname'),
            'housenumber' => request('housenumber'),
            'zipcode' => request('zipcode'),
            'city' => request('city'),
            'description' => request('description'),
            'boardgame_id' => request('game'),
        ])->save();

        return redirect()->route("TournamentAdminEdit", ['tournament' => $tournament->id]);
    }

    /**
     * starts tournement to start status
     * @param Tournament $tournament
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function start(Tournament $tournament)
    {
        $round = ($tournament->tournementUser->max('round') + 1);

        if ($round > $tournament->rounds) {
            return redirect()->back();
        }

        if (count($tournament->registrations->where('accepted', 2)) == 0) {
            session()->flash('error', 'Er zijn nog geen gebruikers die mee doen met dit tournament');
            return redirect()->back();
        }

        if ($tournament->status_id == current(current(\App\Status::where('name', 'Public Open')->where('allowed', 'App\Tournament')->pluck('id')))):
            $tournament->status_id = current(current(\App\Status::where('name', 'Public In progress')->where('allowed', 'App\Tournament')->pluck('id')));
            $tournament->save();
        elseif ($tournament->status_id == current(current(\App\Status::where('name', 'Private Open')->where('allowed', 'App\Tournament')->pluck('id')))):
            $tournament->status_id = current(current(\App\Status::where('name', 'Private In progress')->where('allowed', 'App\Tournament')->pluck('id')));
            $tournament->save();
        endif;

        return view('tournament.admin.start', compact('tournament', 'round'));
    }

    /**
     * creates tournament
     * @param Tournament $tournament
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startSave(Tournament $tournament)
    {
        $round = 0;
        for ($i = 1; $i <= request('rounds'); $i++):
            if (request('round' . $i) != null) {
                $round = $i;
                foreach (request('round' . $i) as $key => $user_id):
                    if ($user_id != 0 && str_contains($key, 'speler')):
                        TournamentUser::create([
                            'user_id' => $user_id,
                            'tournament_id' => $tournament->id,
                            'round' => $i,
                            'round_group' => (int)request('round' . $i)[str_replace('speler', 'group', $key)],
                        ]);
                    endif;
                endforeach;
            }
        endfor;

        session()->flash('success', 'Ronde '.$round.' succesvol aangemaakt');
        return redirect()->route('TournamentAdminEdit', compact('tournament'));
    }

    /**
     * gets score
     * @param Tournament $tournament
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function score(Tournament $tournament)
    {
        if (request('round') == null || count($tournament->tournementUser->where('round', request('round'))) == 0) {
            return redirect()->back();
        }

        $rounds = $tournament->tournementUser->where('round', request('round'));

        return view('tournament.admin.score', compact('tournament', 'rounds'));
    }

    /**
     * saves score and for user
     * @param Tournament $tournament
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveScore(Tournament $tournament)
    {
        if (request('round') == null || count($tournament->tournementUser->where('round', request('round'))) == 0) {
            return redirect()->back();
        }

        //save
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'user') !== false) {
                $update = [
                    "score" => (int)(isset(request($key)['score']) ? request($key)['score'] : 0),
                ];

                if (isset(request($key)['win'])) {
                    $update['win'] = 1;
                }

                $tournament->tournementUser->where('id', substr($key, 4))->first()->fill($update)->save();
            }
        }

        return redirect()->route('TournamentAdminEdit', compact('tournament'));
    }

    /**
     * requests all selected users and send invite
     * @param Tournament $tournament
     * @param InviteRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invite(Tournament $tournament, InviteRequest $request)
    {
        $i = 0;
        foreach ($request['users'] as $user) {
            if (!$tournament->users->contains($user)) {
                TournamentRegistration::create([
                    'user_id' => $user,
                    'tournament_id' => $tournament->id,
                    'status_id' => current(current(\App\Status::where('name', 'Request')->where('allowed', 'App\TournamentRegistration')->pluck('id'))),
                    'accepted' => 0,
                ]);
                $i++;
            }
        }

        if ($i > 0) {
            session()->flash('success', 'De gebruikers zijn succesvol uitgenodigd');
        } else {
            session()->flash('error', 'Er zijn geen nieuwe gebuikers uitgenodigd');
        }

        return redirect()->back();
    }

    /**
     * Function for completing a tournament
     * IF all scores have been entered
     * @param Tournament $tournament
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Tournament $tournament)
    {
        // Check if all scores are entered, if not display an error message
        $tournuser = TournamentUser::where('tournament_id', $tournament->id)->where('score', 0)->first();
        if ($tournuser != null) {
            $message = 'Er is nog geen score ingevuld voor ronde ' . $tournuser->round . '!';
            session()->flash('error', $message);
            return redirect()->back();
        } else {
            // If all scores are set, change the state to closed and save
            if ($tournament->status->name == 'Public In progress') {
                $tournament->status_id = Status::where('name', 'Public Closed')->first()->id;
            } else if ($tournament->status->name = 'Private In progress') {
                $tournament->status_id = Status::where('name', 'Private Closed')->first()->id;
            }
            $tournament->save();

            // Giving the wins and losses to the users
            // Giving the points to the users
            $results = TournamentUser::where('tournament_id', $tournament->id)->get();
            foreach ($results as $result){
                $user = User::find($result->user_id);
                if ($result->win == 1) {
                    $user->wins++;
                } else {
                    $user->losses++;
                }
                $user->points += $result->score;
                $user->save();
            }

            // Confirming the tournament has been completed and redirecting to overview
            $getachievement = new AchievementsController();
            $getachievement->checkConditionRequirement($tournament->id);
            session()->flash('success', 'Tournament succesvol afgesloten!');
            return redirect()->route('TournamentAdmin');
        }
    }
}
