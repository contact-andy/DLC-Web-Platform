<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'title2',
        'title3',
        'title4',
        'title5',
        'title6',
        'title7',
        'title8',
        'title9',
        'title10',
        'title11',
        'description',
        'photo'
    ];
}
