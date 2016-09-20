<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App\Model\Championship
 */
class Team extends Model
{
    use PlayerRelationable;
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['name', 'emblem', 'captain', 'tournament_id','updated_by','updated_on'];

    /**
     * Get game which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        $tournament = $this->tournament()->first();
        return Game::
            join('tournaments','games.id', '=', 'tournaments.game_id')
            ->leftJoin('teams','tournaments.id', '=', 'teams.tournament_id')
            ->where('tournaments.id', '=', $tournament->id)
            ->where('games.id', '=', $tournament->game_id)
            ->where('teams.id', '=', $this->id)
            ->select('games.*')
            ;
    }
    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\Championship\Tournament','tournament_id');
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function playerRelation()
    {
        return $this->morphMany('App\Models\Championship\PlayerRelationable', 'relation');
    }

    /**
     * Get team captain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function captain()
    {
        if (!$this->getAttribute('captain')) {
            return false;
        }
        return Player::findOrFail($this->getAttribute('captain'));
    }


//    public function addPlayer(Player $player){
//        $tournament = $this->tournament;
//        PlayerRelationable::addPlayer();
//        PlayerRelationable::addPlayer();
//        return true;
//    }

}
