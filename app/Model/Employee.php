<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'name', 'image_name', 'address', 'phone_number', 'slug'
    ];
}
