<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'periods';
    /** @use HasFactory<\Database\Factories\PeriodFactory> */
    use HasFactory;
    protected $fillable = ['period'];

    public function classApp()
    {
        return $this->hasMany(ClassApp::class, 'period_id');
    }
}
