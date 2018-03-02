<?php
declare(strict_types = 1);

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class ModelObserver
{

    /**
     * Listen to the model created event.
     *
     * @param Model $model
     */
    public function created(Model $model)
    {
        Session::flash(
            'message',
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
            'message',
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
            'message',
            __('The ' . rtrim($model->getTable(), 's') . ' has been deleted')
        );
    }

}
