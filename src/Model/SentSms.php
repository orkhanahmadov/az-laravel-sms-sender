<?php

namespace Orkhanahmadov\LaravelAzSmsSender\Model;

use Illuminate\Database\Eloquent\Model;

class SentSms extends Model
{
    protected $fillable = [
        "provider",
        "task_id",
        "response_code"
    ];

    public function recipients() {
        return $this->hasMany('Orkhanahmadov\LaravelAzSmsSender\Model\SentSmsNumber');
    }
}
