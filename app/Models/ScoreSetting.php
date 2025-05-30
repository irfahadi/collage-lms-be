<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreSetting extends Model
{
    protected $table = 'score_setting';
    /** @use HasFactory<\Database\Factories\ScoreSettingFactory> */
    use HasFactory;
    protected $fillable = ['percent_value', 'class_id', 'score_type_id'];
    
    public function classApp() {
        return $this->belongsTo(ClassApp::class);
    }

    public function scoreType() {
        return $this->belongsTo(ScoreType::class);
    }

    public function studentScore()
    {
        return $this->hasMany(StudentScore::class, 'score_setting_id');
    }
}
