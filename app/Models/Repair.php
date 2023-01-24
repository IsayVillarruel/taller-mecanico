<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'repairs';

    public function JobEntry()
    {
        return $this->hasOne('App\Models\JobEntry', 'id', 'job_id');
    }

    public function Service()
    {
        return $this->hasOne('App\Models\Service', 'id', 'service_id');
    }

    public function User()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
