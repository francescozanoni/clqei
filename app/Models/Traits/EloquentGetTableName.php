<?php
declare(strict_types = 1);

namespace App\Models\Traits;

/**
 * Trait EloquentGetTableName
 *
 * Inspired by https://github.com/laravel/framework/issues/1436
 *
 * @package App\Models\Traits
 */
trait EloquentGetTableName
{

    /**
     * @return string
     */
    public static function getTableName() : string
    {
        return ((new self)->getTable());
    }

}
