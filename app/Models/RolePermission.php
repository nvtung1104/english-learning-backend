<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    protected function casts(): array
    {
        return [
            'role_id' => 'integer',
            'permission_id' => 'integer',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}