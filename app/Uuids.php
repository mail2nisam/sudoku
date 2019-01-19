<?php
/**
 * Created by PhpStorm.
 * User: nisam
 * Date: 16/1/19
 * Time: 11:38 PM
 */

namespace App;
use Webpatser\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}