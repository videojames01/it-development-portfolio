<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['section', 'name', 'complete'];

    // Tasks belong to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
