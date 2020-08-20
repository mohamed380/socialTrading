<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Chat;
class Message extends Model
{
    //
    protected $fillable = [
        'message', 'sourceID','destinationID','destinationState','chat_id',
    ];

    public function Chat()
    {
        return $this->belongsTo('App\Chat');
    }

}
