<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends \Nahid\Talk\Messages\Message
{
    //
    use \Spiritix\LadaCache\Database\LadaCacheTrait;
    public function getUnreadMessagesCount() {
         return $this->select('id')->where('user_id','!=',auth()->user()->id)->where('is_seen',0)->count();
     }

    public function getSentMessages() {
         return $this->where('user_id', auth()->user()->id)->get();
     }

    public function sender()
    {
        return $this->belongsTo('\App\Models\User', 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

}
