<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobsList extends Model
{
    protected $fillable = array('id', 'address_1', 'address_2', 'city', 'state', 'zip', 'billto', 'orders', 'service_id', 'technician_id', 'service_description', 'detail', 'work_date',  'time_range', 'order_time', 'order_instruction', 'service_instructions');
}
