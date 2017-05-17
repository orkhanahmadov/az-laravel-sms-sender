<?php

namespace Orkhanahmadov\LaravelAzSmsSender\Model;

use Illuminate\Database\Eloquent\Model;

class SentSmsNumber extends Model
{
    protected $fillable = [
        "number",
        "message"
    ];

    public function sent() {
        return $this->belongsTo(SentSms::class);
    }
}
