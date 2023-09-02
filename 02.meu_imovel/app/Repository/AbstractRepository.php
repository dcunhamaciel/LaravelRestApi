<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function selectFilter($columns) 
    {   
        $this->model = $this->model->selectRaw($columns);
    }

    public function selectConditions($conditions)
    {
        $expressions = explode(';', $conditions);

        foreach($expressions as $expression) {
            $exp = explode(':', $expression);
            
            $this->model = $this->model->where($exp[0], $exp[1], $exp[2]);
        }
    }

    public function getResult()
    {
        return $this->model;
    }
}
