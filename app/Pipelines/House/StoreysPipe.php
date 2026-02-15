<?php

namespace App\Pipelines\House;

use App\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class StoreysPipe extends Pipe
{
    protected function apply(Builder $query, mixed $value): void
    {
        $query->where('storeys', $value);
    }
}
