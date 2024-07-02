<?php

namespace App\Services\JobProfile\BaseService;

use App\Models\JobProfile;
use App\Exceptions\ModelException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


abstract class JobBaseService
{
    /**
     * @var JobProfile
     */
    protected JobProfile $model;

    /**
     * @return JobProfile
     */
    abstract protected function getModelObject(): JobProfile;

    /**
     * @return JobProfile
     * @throws ModelNotFoundException
     */
    public function getModel(): JobProfile
    {
        if (!isset($this->model)) {
            throw new ModelNotFoundException("Model not loaded");
        }

        return $this->model;
    }

    /**
     * @param JobProfile $model
     * @return void
     */
    public function setModel(JobProfile $model): void
    {
        $this->model = $model;
    }

    /**
     * Model is unset by setting it to null
     */
    public function unsetModel(): void
    {
        unset($this->model);
    }

    /**
     * @param JobProfile $model
     * @throws ModelException
     */
    public function insert(JobProfile $model)
    {
        if (!$model->save()) {
            throw new ModelException('Failed to insert model');
        }

        $this->setModel($model);
    }

    /**
     * @param JobProfile $model
     * @throws ModelException
     */
    public function update(JobProfile $model)
    {
        if (!$model->update()) {
            throw new ModelException('Failed to update model');
        }

        $this->setModel($model);
    }

    /**
     * @param JobProfile $model
     * @throws ModelException
     */
    public function delete(JobProfile $model)
    {
        if (!$model->delete()) {
            throw new ModelException('Failed to delete model');
        }

        $this->unsetModel();
    }
}
