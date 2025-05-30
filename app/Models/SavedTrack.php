<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SavedTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'spotify_track_id',
        'track_name',
        'artist',
        'album_art',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
