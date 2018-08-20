<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /* Table Name (if want to change to plular)
    protected $table = 'posts';
    */

    //Table name
    protected $table = 'posts';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }
}
