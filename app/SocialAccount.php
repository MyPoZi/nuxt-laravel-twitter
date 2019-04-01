<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $table = 'user_social_accounts';

    protected $fillable = ["provider", "account_id"];

    public $timestamps = false;
}
