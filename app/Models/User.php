<?php

namespace App\Models;

use App\Notifications\AiUsedNotification;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass-assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
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

    public static function notification(string $message, string $client): void
    {
        $notification = new AiUsedNotification($message, $client);
        $notification->onQueue('notifications');

        if (auth()->check()) {
            auth()->user()->notify($notification);

            return;
        }

        try {
            self::where('email', Config::string('constants.admin_user'))
                ->firstOrFail()
                ->notify($notification);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * The attributes that should be cast.
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
