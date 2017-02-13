<?php

namespace Orkhanahmadov\LaravelAzSmsSender\Model;

use Illuminate\Database\Eloquent\Model;

class SentSmsNumber extends Model
{
    protected $fillable = [
        "sent_sms_id",
        "number",
        "message"
    ];

    public function sent() {
        return $this->belongsTo('Orkhanahmadov\LaravelAzSmsSender\Model\SentSms');
    }
}
