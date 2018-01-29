<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//test links
Route::get('/getachievement','admin\AchievementsController@checkConditionRequirement')->name('test');
//

Route::get('/profile/{user}', 'UserController@show')->name('profileView');

Route::get('/profileEdit/{user}', 'UserController@edit')->name('profileEdit')->middleware('auth');
Route::post('/profile', 'UserController@save')->name('profileSave')->middleware('auth');

Route::get('/', 'HomeController@index')->name('root');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

// Suggestions
Route::get('/suggest', 'SuggestionController@create')->name('suggestion')->middleware('auth');
Route::post('/suggest', 'SuggestionController@save')->name('suggestionSave')->middleware('auth');

Route::get('search', 'SearchController@show')->name('search');

Route::get('boardgame/{boardgame}', 'BoardgameController@show')->name('boardgame');
Route::get('boardgame/{id}/vote/{value}', 'BoardgameController@vote')->name('boardgameVote')->middleware('auth');
Route::get('boardgamef/{boardgame}', 'BoardgameController@addFavorite')->name('boardgameAddFavorite')->middleware('auth');
Route::get('boardgamed/{boardgame}', 'BoardgameController@delFavorite')->name('boardgameDelFavorite')->middleware('auth');

Route::post('searchBoardgameRankingsAdmin', 'admin\BoardgameController@searchBoardgame')->name('searchBoardgameRankingsAdmin')->middleware('role:admin');

Route::group(['prefix' => 'select2'], function () {
    Route::get('boardgames', 'Select2Controller@boardgames')->name('boardgamesJson');
    Route::get('achievements', 'Select2Controller@achievements')->name('achievementsJson');
    Route::get('users', 'Select2Controller@users')->name('usersJson');
});

Route::group(['prefix' => 'request', 'middleware' => ['auth']], function () {
    Route::get('/', 'TournamentController@requestIndex')->name('requests');
    Route::get('{tournament_request}/{state}', 'TournamentController@request')->name('requestState');
});

Route::group(['prefix' => 'tournament'], function () {
    Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
        Route::get('/', 'admin\TournamentController@index')->name('TournamentAdmin');
        Route::get('/{tournament}', 'admin\TournamentController@edit')->name('TournamentAdminEdit');
        Route::patch('/{tournament}/edit', 'admin\TournamentController@store')->name('TournamentAdminStore');
        Route::post('/{tournament}/invite', 'admin\TournamentController@invite')->name('TournamentAdminInvite');
        Route::get('/{tournament}/start', 'admin\TournamentController@start')->name('TournamentAdminStart');
        Route::post('/{tournament}/start', 'admin\TournamentController@startSave')->name('TournamentAdminStartSave');
        Route::get('/{tournament}/score', 'admin\TournamentController@score')->name('TournamentAdminScore');
        Route::post('/{tournament}/score', 'admin\TournamentController@saveScore')->name('TournamentAdminScoreSave');
        Route::get('/{tournament}/complete', 'admin\TournamentController@complete')->name('TournamentAdminComplete');
    });

    Route::get('/', 'TournamentController@index')->name('tournamentList');
    Route::get('create', 'TournamentController@create')->name('createTournament')->middleware('auth');
    Route::post('create', 'TournamentController@save')->name('createTournamentPost')->middleware('auth');
    Route::get('{tournament}', 'TournamentController@show')->name('tournament');
    Route::get('{tournament}/register', 'TournamentController@register')->name('tournamentRegister')->middleware('auth');
});
Route::group(['prefix' => 'rankings'], function () {
    Route::get('/', 'RankingController@index')->name('rankings');
    Route::get('/rankingsbylosses', 'RankingController@rankingsbylosses')->name('rankingsbylosses');
    Route::post('searchUsersRanking', 'RankingController@searchUsers')->name('searchUsersRanking');
    Route::post('searchBoardgameRankings', 'RankingController@searchBoardgamepost')->name('searchBoardgameRankings');
    Route::get('searchBoardgameRankings{boardgameid}', 'RankingController@searchBoardgameget')->name('searchBoardgameRankingsget');
});

Route::group(['prefix' => 'admin', 'middleware' => 'role:admin'], function () {

    Route::group(['prefix' => 'boardgame'], function () {
        Route::get('/', 'admin\BoardgameController@index')->name('AdminBoardgame');
        Route::get('/create', 'admin\BoardgameController@create')->name('AdminBoardgameCreate');
        Route::post('/create', 'admin\BoardgameController@save')->name('AdminBoardgameSave');
        Route::get('/edit/{boardgame?}', 'admin\BoardgameController@edit')->name('AdminBoardgameEdit');
        Route::patch('/store', 'admin\BoardgameController@store')->name('AdminBoardgameStore');
        Route::delete('/delete', 'admin\BoardgameController@delete')->name('AdminBoardgameDelete');
    });

    Route::group(['prefix' => 'achievements'], function () {
        Route::get('/', 'admin\AchievementsController@index')->name('adminAchievement');
        Route::delete('/deleteachievement/{id}', 'admin\AchievementsController@deleteAchievement')->name('adminAchievementDelete');
        Route::get('/addachievements', 'admin\AchievementsController@addAchievementIndex')->name('adminAchievementCreate');
        Route::post('/addachievements', 'admin\AchievementsController@postAddAchievement')->name('adminAchievementSave');
        Route::put('/checkachievement/{id}', 'admin\AchievementsController@checkAchievement')->name('adminAchievementCheck');
        Route::post('/updateachievement/{id}', 'admin\AchievementsController@postUpdateAchievement')->name('adminAchievementUpdate');
        Route::post('searchBoardgameRankingsAdmin', 'admin\AchievementsController@searchAchievements')->name('searchAchievementsAdmin');
    });

    Route::group(['prefix' => 'suggestions'], function () {
        Route::get('/', 'admin\SuggestionController@read')->name('suggestionHandle');
        Route::get('/accept/{id?}', 'admin\SuggestionController@accept')->name('suggestionAccept');
        Route::delete('/delete/{id?}', 'admin\SuggestionController@delete')->name('suggestionDecline');
    });
});
