<?php

namespace App\Http\Controllers;

use App\Boardgame;
use App\Http\Requests\TournamentRequest;
use App\Tournament;
use App\TournamentRegistration;
use Illuminate\Http\Request;
use Auth;
use App\Status;
use Illuminate\Support\Facades\Input;

/**
 * Class TournamentController
 * @package App\Http\Controllers
 */
class TournamentController extends Controller
{
    /**
     * Get all tournaments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $tournaments = Tournament::orderBy('status_id');

        switch (Input::get('filter')) {
            case 'open':
                $tournaments->whereIn('status_id', current(Status::whereIn('name', ['Public Open', 'Private Open'])->where('allowed','App\Tournament')->pluck('id')));
                break;
            case 'in_progress':
                $tournaments->whereIn('status_id', current(Status::whereIn('name', ['Public In progress', 'Private In progress'])->where('allowed','App\Tournament')->pluck('id')));
                break;
            case 'closed':
                $tournaments->whereIn('status_id', current(Status::whereIn('name', ['Public Closed', 'Private Closed'])->where('allowed','App\Tournament')->pluck('id')));
                break;
        }

        $tournaments = $tournaments->orderBy('startdate')->paginate(25)->appends(Input::only('filter'));
        return view('tournament/index', ['tournaments' => $tournaments]);
    }

    /**
     * Show the tournament create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('tournament/add');
    }

    /**
     * Add a tournament
     * @param TournamentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(TournamentRequest $request){
        $tournament = Tournament::create([
            'name' => $request['name'],
            'rounds' => $request['rounds'],
            'minplayers' => $request['minplayers'],
            'maxplayers' => $request['maxplayers'],
            'startdate' => date('Y-m-d',strtotime($request['startdate'])),
            'streetname' => $request['streetname'],
            'housenumber' => $request['housenumber'],
            'zipcode' => $request['zipcode'],
            'city' => $request['city'],
            'description' => $request['description'],
            'boardgame_id' => $request['game'],
            'status_id' => current(current(\App\Status::where('name', (isset($request['private']) && $request['private'] == 1 ? 'Private Open' : 'Public Open'))->where('allowed','App\Tournament')->pluck('id'))),
            'user_id' => Auth::user()->id,
        ]);

        foreach($request['users'] as $user_id){
            TournamentRegistration::create([
                'user_id' => $user_id,
                'tournament_id' => $tournament->id,
                'status_id' => current(current(\App\Status::where('name', 'Request')->where('allowed','App\TournamentRegistration')->pluck('id'))),
                'accepted' => 0,
            ]);
        }

        session()->flash('success', 'Het toernooi is succesvol aangemaakt');
        return redirect()->route('tournamentList');
    }

    /**
     * Register to an tournament
     * @param Tournament $tournament
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Tournament $tournament){
        if(
            $tournament->status->id !== current(current(\App\Status::where('name', 'Public Open')->where('allowed','App\Tournament')->pluck('id'))) ||
            count($tournament->registrations->where('user_id', Auth::user()->id)) >= 1
        ){
            return redirect()->route('tournamentList');
        }

        TournamentRegistration::create([
            'user_id' => Auth::user()->id,
            'tournament_id' => $tournament->id,
            'status_id' => current(current(\App\Status::where('name', 'Register')->where('allowed','App\TournamentRegistration')->pluck('id'))),
            'accepted' => 2,
        ]);

        session()->flash('success', 'Je hebt je succesvol ingeschreven voor dit tournament');
        return back();
    }

    /**
     * Show tournament info
     * @param Tournament $tournament
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Tournament $tournament){
        if(
            in_array($tournament->status->id, current(\App\Status::whereIn('name', ['Private Open', 'Private In progress', 'Private Closed'])->where('allowed','App\Tournament')->pluck('id'))) &&
            count(current($tournament->registrations->where('user_id', Auth::user()->id)->whereIn('accepted', [0,2]))) == 0 &&
            $tournament->user_id !== Auth::user()->id /* If the user is the owner of the tournament */
        ){
            return redirect()->route('tournamentList');
        }
        return view('tournament.show',['tournament'=>$tournament]);
    }

    /**
     * Show all tournament request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requestIndex(){
        $requests = TournamentRegistration::where('user_id', Auth::user()->id)->get();
        return view('tournament.request', ['requests'=>$requests]);
    }

    /**
     * Update tournament request state
     * @param TournamentRegistration $tournament_request
     * @param $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function request(TournamentRegistration $tournament_request, $state){
        if($tournament_request->user_id != Auth::user()->id){
            return redirect()->route('requests');
        }
        $state = ($state == 2 ? $state : 1);// 1 = Decline, 2 = Accept
        $tournament_request->update(['accepted'=>$state]);
        return redirect()->route('requests');
    }
}
