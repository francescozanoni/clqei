<?php
declare(strict_types = 1);

namespace App\Observers;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class EloquentModelObserver
{

    const FLASH_MESSAGE_KEY = "message";

    /**
     * Listen to the model created event.
     *
     * @param Model $model
     */
    public function created(Model $model)
    {
        Session::flash(
            self::FLASH_MESSAGE_KEY,
            __("The new " . $this->getModelName($model) . " has been created")
        );
    }

    /**
     * Get a human readable string representation of the model class.
     *
     * @param Model $model
     * @return string
     */
    private function getModelName(Model $model) : string
    {
        switch (get_class($model)) {
            case User::class:
                return $model->role;
            default:
                return rtrim($model->getTable(), "s");
        }
    }

    /**
     * Listen to the model updated event.
     *
     * @param Model $model
     */
    public function updated(Model $model)
    {
        Session::flash(
            self::FLASH_MESSAGE_KEY,
            __("The " . $this->getModelName($model) . " has been updated")
        );
    }

    /**
     * Listen to the model deleted event.
     *
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        Session::flash(
            self::FLASH_MESSAGE_KEY,
            __("The " . $this->getModelName($model) . " has been deleted")
        );
    }

    /**
     * Listen to the model restored event.
     *
     * @param Model $model
     */
    public function restored(Model $model)
    {
        Session::flash(
            self::FLASH_MESSAGE_KEY,
            __("The " . $this->getModelName($model) . " has been restored")
        );
    }

}
