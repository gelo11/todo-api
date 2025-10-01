<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status'];

    protected $casts = [
        'status' => 'integer',
    ];

    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = strip_tags($value);
    }

    public function setStatusAttribute($value): void
    {
        $allowed = [1, 2, 3, 4, 5];
        $this->attributes['status'] = in_array($value, $allowed) ? $value : 1;
    }
}
