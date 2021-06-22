<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;

/**
 * Class ProjectEloquentResponsitory
 * @package App\Repositories\Eloquent
 */
class ProjectEloquentResponsitory extends EloquentBaseReponsitory implements ProjectRespositoryInterface
{
    /**
     * @return string
     */
    public function setModel()
    {
        return Project::class;
    }

    public function getImageFolder()
    {
        // TODO: Implement getImageFolder() method.
        return $this->_model->getImageFolder();
    }
}
