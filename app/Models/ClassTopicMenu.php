<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTopicMenu extends Model
{
    protected $table = 'class_topic_menus';
    /** @use HasFactory<\Database\Factories\ClassTopicMenuFactory> */
    use HasFactory;
    protected $fillable = ['menu', 'is_modul', 'is_exam', 'class_topic_id'];
    
    public function classTopic() {
        return $this->belongsTo(ClassTopic::class);
    }

}
