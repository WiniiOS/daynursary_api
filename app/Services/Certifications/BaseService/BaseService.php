<?php

namespace App\Services\Certifications\BaseService;

use App\Models\Certification;
use App\Exceptions\ModelException;
use App\Models\ProfileCertification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract Class BaseService
{
     /**
     * @var Certification
     */
    protected Certification $model;

    /**
     * @param string $param
     * @return Certification|ProfileCertification
     */
    abstract protected function getModelObject(string $param): Certification|ProfileCertification;

    /**
     * @return Certification
     * @throws ModelNotFoundException
     */
    public function getModel(): Certification
    {
        if (!isset($this->model)) {
            throw new ModelNotFoundException("Model not loaded");
        }

        return $this->model;
    }

    /**
     * @param Certification $model
     * @return void
     */
    public function setModel(Certification $model): void
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
     * @param Certification $model
     * @throws ModelException
     */
    public function insert(Certification $model)
    {
        if (!$model->save()) {
            throw new ModelException('Failed to insert model');
        }

        $this->setModel($model);
    }

    /**
     * @param Certification $model
     * @throws ModelException
     */
    public function update(Certification $model)
    {
        if (!$model->update()) {
            throw new ModelException('Failed to update model');
        }

        $this->setModel($model);
    }

    /**
     * @param Certification $model
     * @throws ModelException
     */
    public function delete(Certification $model)
    {
        if (!$model->delete()) {
            throw new ModelException('Failed to delete model');
        }

        $this->unsetModel();
    }
}