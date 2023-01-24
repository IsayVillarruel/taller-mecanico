<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UserType()
    {
        return $this->hasOne('App\Models\UserType', 'id', 'user_type_id');
    }

    public function hasPerm($paramIdPerm)
    {
        
        $perm = json_decode($this->perm);

        if($perm == null)
        {
            $perm = array();
        }

        foreach($perm as $item) 
        {
            if(!property_exists($item, "idModule"))
            {
                return false;
            }

            if($item->idModule == $paramIdPerm)
            {
                return true;
                break;
            }
        }   

        return false;    
    }

    public function hasPermIntern($paramIdPerm,$intern)
    {
        $perm = json_decode($this->perm);

        if($perm == null)
        {
            $perm = array();
        }

        foreach($perm as $item) 
        {
            if(!property_exists($item, "idModule"))
            {
                return false;
            }

            if($item->idModule == $paramIdPerm)
            {
                foreach($item->permsInters as $itemNamePerm)
                {
                    if($itemNamePerm == $intern)
                    {
                        return true;
                    }
                }

                return false;
            }
        }   

        return false;        
    }

    public function isAdmin($type)
    {
        $iUserType = $this->user_type_id;
        
        if($type === $iUserType)
        {
            return true;
        }

        return false;
    }
}
