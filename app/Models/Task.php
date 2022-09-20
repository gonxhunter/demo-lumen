<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_ids', 'due_date', 'parent_id'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
