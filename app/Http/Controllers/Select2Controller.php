<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\boardgame;
use App\achievement;
use App\User;
use Illuminate\Support\Facades\Input;

/**
 * Class Select2Controller
 * @package App\Http\Controllers
 */
class Select2Controller extends Controller
{
    /**
     * Convert data to Select2 json string
     * @param $data
     * @return string
     */
    public function toJson($data, $more = false){
        return json_encode(['results'=>$data,'pagination'=>['more'=>$more]]);
    }

    /**
     * Get all boardgames with optional search
     * @return string
     */
    public function boardgames(){
        $data = Boardgame::where('name','LIKE','%'.request('q').'%')->orderBy('name', 'asc')->select(['id', 'name as text'])->paginate(25);
        return $this->toJson($data->toArray()['data'], $data->nextPageUrl() ? true : false);
    }

    public function achievements(){
        $data = achievement::where('name','LIKE','%'.request('q').'%')->orderBy('name', 'asc')->select(['id', 'name as text'])->paginate(25);
        return $this->toJson($data->toArray()['data'], $data->nextPageUrl() ? true : false);
    }

    /**
     * Get all users with optional search
     * @return string
     */
    public function users(){
        $users = User::where('username','LIKE','%'.request('q').'%');

        if(request('tournament') != null){
            $users->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->where('tournament_id', request('tournament'))
                    ->from('tournament_registrations');
            });
        }

        //Pagination >= 5 for select 2
        $data = $users->select(['id','username as text'])->paginate(25)->appends(Input::only('q','tournament'));

        return $this->toJson($data->toArray()['data'], $data->nextPageUrl() ? true : false);
    }
}
