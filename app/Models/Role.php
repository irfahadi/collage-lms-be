<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $connection = 'mariadb_second';
    protected $table = 'roles';
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;
    protected $fillable = ['role'];

    public function user()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
