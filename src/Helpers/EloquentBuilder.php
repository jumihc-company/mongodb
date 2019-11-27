<?php

namespace Jmhc\Mongodb\Helpers;

use Hyperf\Database\Model\Builder;

class EloquentBuilder extends Builder
{
    use QueriesRelationships;
}
