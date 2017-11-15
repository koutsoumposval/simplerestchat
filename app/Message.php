<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class Message extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];
}