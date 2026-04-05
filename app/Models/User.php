<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }
    public function clientProfiles()
    {
        return $this->hasMany(ClientProfile::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(
            Organization::class,
            'client_profiles'
        );
    }


    // Define the many-to-many relationship with roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->using(\App\Models\Pivots\RoleUser::class);
    }

    // Define the many-to-many relationship with permissions through roles
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }

    // User.php
    public function currentRole(): string
    {
        return $this->roles->first()?->name ?? 'guest';
    }

    public function organizationId(): ?int
    {
        return $this->clientProfile?->organization_id;
    }
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }


    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    // Custom method to assign a role to the user
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $this->roles()->attach($role);
        } else {
            throw new \Exception("Role '{$roleName}' does not exist.");
        }
    }
    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }
    public function profileImage()
    {
        return $this->media()->first();
    }

    // Checking if user has a specific permission
    public function hasPermissionTo($permissionName)
    {
        // Check if the user has the permission directly through the user-to-permission relationship
        $directPermission = $this->permissions()->where('permissions.name', $permissionName)->exists();

        // Check if the user has the permission through any of their roles
        $rolePermission = $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->pluck('name')->contains($permissionName);
        // Return true if the user has the permission either directly or through a role
        return $directPermission || $rolePermission;
    }
}
