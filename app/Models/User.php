<?php

namespace App\Models;

use Filament\Navigation\NavigationItem;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function getNavigation($route)
    {
        return match ($this->role_id) {
            Role::ADMIN => [],
            Role::PASSENGER => [
                '' => [
                    'collapsible' => false,
                    'items' => [
                        [
                            'group' => null,
                            'active' => $route == 'passenger.fare_matrix',
                            'icon' => "heroicon-o-cash",
                            'label' => "Fare Matrix",
                            'badge' => null,
                            'shouldOpenUrlInNewTab' => false,
                            'sort' => null,
                            'url' => route('passenger.fare_matrix'),
                        ],
                        [
                            'group' => null,
                            'active' => $route == 'passenger.dispatches',
                            'icon' => "heroicon-o-truck",
                            'label' => "Dispatches",
                            'badge' => null,
                            'shouldOpenUrlInNewTab' => false,
                            'sort' => null,
                            'url' => route('passenger.dispatches'),
                        ],
                        [
                            'group' => null,
                            'active' => $route == 'passenger.bookings',
                            'icon' => "heroicon-o-ticket",
                            'label' => "Bookings",
                            'badge' => null,
                            'shouldOpenUrlInNewTab' => false,
                            'sort' => null,
                            'url' => route('passenger.bookings'),
                        ],
                    ],
                ],
            ],
        };
    }
}
