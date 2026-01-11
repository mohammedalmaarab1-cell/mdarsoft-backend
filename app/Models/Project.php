<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'gallery',
        'tech_stack',
        'link',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'gallery' => 'array',
    ];
}
