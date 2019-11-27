<?php

namespace Jmhc\Mongodb\Eloquent;

trait SoftDeletes
{
    use \Hyperf\Database\Model\SoftDeletes;

    /**
     * @inheritdoc
     */
    public function getQualifiedDeletedAtColumn()
    {
        return $this->getDeletedAtColumn();
    }
}
