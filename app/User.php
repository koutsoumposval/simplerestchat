<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * @package App
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return HasOneOrMany
     */
    public function messages(): HasOneOrMany
    {
        return $this->hasMany(Message::class, 'sender_id', 'id');
    }
}
