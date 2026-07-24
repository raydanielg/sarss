<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'force_password_change', 'last_login_at', 'last_login_ip', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'force_password_change' => 'boolean',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function panelsAsModerator()
    {
        return $this->hasMany(Panel::class, 'moderator_user_id');
    }

    public function panelDataEntries()
    {
        return $this->hasMany(PanelDataEntry::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function marksEntered()
    {
        return $this->hasMany(Mark::class, 'entered_by');
    }

    public function marksVerified()
    {
        return $this->hasMany(Mark::class, 'verified_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function createdExaminations()
    {
        return $this->hasMany(Examination::class, 'created_by');
    }
}
