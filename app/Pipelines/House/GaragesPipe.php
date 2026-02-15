<?php

namespace App\Pipelines\House;

use App\Pipelines\Pipe;
use Illuminate\Database\Eloquent\Builder;

class GaragesPipe extends Pipe
{
    protected function apply(Builder $query, mixed $value): void
    {
        $query->where('garages', $value);
    }
}
