<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'sex',
        'dob',
        'mobile',
        'email',
        'city',
        'password',
        'languageId'
    ];
}