<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;
    protected $fillable = ['full_name', 'title', 'description', 'photo', 'twitter', 'facebook', 'linkedin', 'instagram'];
    
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
