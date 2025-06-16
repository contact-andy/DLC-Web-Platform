<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'fileName',
        'poster',
        'categoryId',
        'languageId',
    ];
}
