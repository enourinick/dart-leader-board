<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Game
 * @package App
 *
 * @property integer id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property integer target_score
 * @property integer winner_id
 *
 * @property User winner
 * @property Collection users
 */
class Game extends Model
{
    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('score')->withTimestamps();
    }
}
