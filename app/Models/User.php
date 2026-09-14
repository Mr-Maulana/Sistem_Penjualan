<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'profile_photo_path',
        'password',
        'nik',
        'profesi',
        'phone',
        'address',
        'gender',
        'birth_date',
        'salesman_id',
    ];

    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }

    /**
     * Get the salesman IDs allowed to be accessed by this user based on their role.
     *
     * @return array|null Null represents all access (admin)
     */
    public function getAllowedSalesmanIds()
    {
        if ($this->role === 'admin') {
            return null;
        }
        if ($this->role === 'sales') {
            return [$this->salesman_id];
        }
        if ($this->role === 'supervisor') {
            if (!$this->salesman_id) {
                return [];
            }
            $subordinateIds = Salesman::where('supervisor_id', $this->salesman_id)->pluck('id')->toArray();
            return array_merge([$this->salesman_id], $subordinateIds);
        }
        if ($this->role === 'manager') {
            $managerSalesmanId = $this->salesman_id;
            if (!$managerSalesmanId) {
                return [];
            }
            $supervisorIds = Salesman::where('supervisor_id', $managerSalesmanId)->pluck('id')->toArray();
            $salesIds = Salesman::whereIn('supervisor_id', $supervisorIds)->pluck('id')->toArray();
            return array_merge([$managerSalesmanId], $supervisorIds, $salesIds);
        }
        return [];
    }

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
}
