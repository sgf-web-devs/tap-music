<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * OnlineUser
 *
 * @property integer $id
 * @property string $userID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\OnlineUser whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\OnlineUser whereUserID($value) 
 * @method static \Illuminate\Database\Query\Builder|\OnlineUser whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\OnlineUser whereUpdatedAt($value) 
 */
class OnlineUser extends Eloquent {

	protected $table = 'online_users';

}
