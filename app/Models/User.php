<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Google\Service\Keep\Resource\Notes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'key',
        'location',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function getCreatedFormatAttribute()
    {
        return Carbon::parse($this->created)->translatedFormat('d M, Y');
    }

    public function getColorStatusAttribute()
    {
        return [
            'active' => 'success',
            'deactivate' => 'danger',
        ][$this->status] ?? 'success';
    }

    public function getRoleNameAttribute()
    {
        return [
            'Admin' => '<i class="fa-solid fa-shield"></i> Administrador',
            'Sales' => '<i class="fa-solid fa-shopping-bag"></i> Vendedor',
            'Guide' => '<i class="fa-solid fa-bullhorn"></i> Guia',
            'Api' => '<i class="fa-solid fa-code-fork"></i> Api',
        ][$this->roles[0]->name] ?? '<i class="fa-solid fa-shopping-bag"></i> Vendedor';
    }

    public function getColorRoleAttribute()
    {
        return [
            'Admin' => 'success',
            'Sales' => 'info',
            'Guide' => 'secundary',
            'Api' => 'dark',
        ][$this->roles[0]->name] ?? 'info';
    }

    /**
     * Get all of the note for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function note(): HasOne
    {
        return $this->hasOne(Note::class, 'id_user', 'id');
    }
    /**
     * Get all of the logs for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'user_id', 'id');
    }
}
