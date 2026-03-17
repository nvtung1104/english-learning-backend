<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'role_id' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}