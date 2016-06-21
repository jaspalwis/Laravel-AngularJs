<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model
{
    protected $fillable = array('id', 'name', 'description','category','status');
}
