<?php

namespace App\Pipelines\House;

use App\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class BathroomsPipe extends Pipe
{
    protected function apply(Builder $query, mixed $value): void
    {
        $query->where('bathrooms', $value);
    }
}
