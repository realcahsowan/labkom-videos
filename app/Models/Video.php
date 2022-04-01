<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'filename', 'hashname', 'description', 'size'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
