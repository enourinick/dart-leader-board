<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GamePlayer
 * @package App
 *
 * @property integer score
 * @property User user
 * @property Game game
 */
class GamePlayer extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }
}
