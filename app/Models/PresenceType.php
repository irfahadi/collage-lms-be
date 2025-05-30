<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceType extends Model
{
    protected $table = 'presence_types';
    /** @use HasFactory<\Database\Factories\PresenceTypeFactory> */
    use HasFactory;
    protected $fillable = ['type'];
}
