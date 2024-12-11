<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'game_id';

    protected $fillable = [
        'season_id', 'week_number', 'game_date', 'team_1_id','team_2_id','team_1_score','team_2_score','team_1_yds','team_2_yds'
    ];

    public function userGamePredictions(){

    }
}
