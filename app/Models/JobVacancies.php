<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobVacancies extends Model
{
    protected $table = 'jobboards';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fullname',
        'age',
        'address',
        'zip',
        'phone',
        'email',
        'schoollevel',
        'experience',
        'opportunities_id',
        'date',
        'eligible'
    ];
}
