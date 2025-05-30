<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulType extends Model
{
    protected $table = 'modul_types';
    /** @use HasFactory<\Database\Factories\ModulTypeFactory> */
    use HasFactory;
    protected $fillable = ['type'];

    public function topicModul()
    {
        return $this->hasMany(TopicModul::class, 'modul_type_id');
    }
}
