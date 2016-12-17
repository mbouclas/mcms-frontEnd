<?php

namespace Mcms\FrontEnd\Models;

use Mcms\Core\Models\User;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //

    /**
     * this will provide a static interface to the Model, Like Content::register([title=>'asas'])
     * We use this to hook up events and stuff to the model
     * @param array $attributes
     * @return static
     */
    public static function register(array $attributes){
        $item = static::create($attributes);
        //fire an event
        event('content.item.created',[$item]);


        return $item;
    }

    /**
     * Return the owner of this item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    /**
     * Check if this user owns this item
     * @param User $user
     * @return bool
     */
    public function ownedBy(User $user)
    {
        return $this->uid == $user->id;
    }
}
