<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDetails extends Model
{
    protected $table = 'jobs_data';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre'
    ];
}
