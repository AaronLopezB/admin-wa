<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImgUser extends Model
{
    protected $table = 'img_user';
    protected $primaryKey = 'id';
    protected $fillabe = [
        'url_img',
        'tipo_foto'
    ];
    public $timestamps = false;
}
