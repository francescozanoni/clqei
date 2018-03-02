<?php
declare(strict_types = 1);

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class ModelObserver
{

    const FLASH_MESSAGE_SESSION_KEY = 'message';

    /**
     * Listen to the model created event.
     *
     * @param Model $model
     */
    public function created(Model $model)
    {
        Session::flash(
            self::FLASH_MESSAGE_SESSION_KEY,
            __('The new ' . rtrim($model->getTable(), 's') . ' has been created')
        );
    }

    /**
     * Listen to the model updated event.
     *
     * @param Model $model
     */
    public function updated(Model $model)
    {
        Session::flash(
            self::FLASH_MESSAGE_SESSION_KEY,
            __('The ' . rtrim($model->getTable(), 's') . ' has been updated')
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
            self::FLASH_MESSAGE_SESSION_KEY,
            __('The ' . rtrim($model->getTable(), 's') . ' has been deleted')
        );
    }

}
