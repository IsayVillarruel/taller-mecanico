<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobEntry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'job_entry';

    public function Car()
    {
        return $this->hasOne('App\Models\Car', 'id', 'car_id');
    }

    public function Status()
    {
        return $this->hasOne('App\Models\Status', 'id', 'status_id');
    }

    public function Repairs()
    {
        return $this->hasMany('App\Models\Repair', 'job_id', 'id');
    }
}
