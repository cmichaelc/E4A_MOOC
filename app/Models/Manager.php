<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_id',
    ];

    /**
     * Get the user that owns the manager profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school this manager manages
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
