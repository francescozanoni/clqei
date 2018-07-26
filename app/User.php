<?php
declare(strict_types = 1);

namespace App;

use App\Models\Traits\EloquentGetTableName;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Yadahan\AuthenticationLog\AuthenticationLogable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use EloquentGetTableName;
    use AuthenticationLogable;

    const ROLE_ADMINISTRATOR = 'administrator';
    const ROLE_VIEWER = 'viewer';
    const ROLE_STUDENT = 'student';

    const EXAMPLE_DOMAIN = 'example.com';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Set the user's first name.
     *
     * @param  string $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value), '\'- ');
    }

    /**
     * Set the user's last name.
     *
     * @param  string $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower($value), '\'- ');
    }

    /**
     * Get the student of this user
     * @return HasOne
     */
    public function student() : HasOne
    {
        return $this->hasOne('App\Models\Student');
    }

    /**
     * Scope a query to only include student users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudents(Builder $query) : Builder
    {
        return $query->where('role', self::ROLE_STUDENT);
    }

    /**
     * Scope a query to only include viewer users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewers(Builder $query) : Builder
    {
        return $query->where('role', self::ROLE_VIEWER);
    }

    /**
     * Scope a query to only include administrator users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministrators(Builder $query) : Builder
    {
        return $query->where('role', self::ROLE_ADMINISTRATOR);
    }

    /**
     * Send the password reset notification.
     * This method is overridden in order to customize/localize the message
     * and prevent e-mail delivery related to example users.
     *
     * @param string $token
     *
     * @see https://laravel.com/docs/5.5/passwords#password-customization
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->isExampleUser() === true) {
            return;
        }
        $this->notify(new ResetPasswordNotification($token));
    }

    public function isExampleUser() : bool
    {
        return substr($this->email, -strlen('@' . self::EXAMPLE_DOMAIN)) === '@' . self::EXAMPLE_DOMAIN;
    }

}
