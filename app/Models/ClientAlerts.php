<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAlerts extends Model
{
    protected $table = 'alertclientes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'por',
        'email_alert'
    ];
    public $timestamps = false;
}
