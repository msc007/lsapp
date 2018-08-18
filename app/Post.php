<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /* Table Name (if want to change to plular)
    protected $table = 'posts';
    */

    //primary key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
