<?php

namespace App\Pipelines\House;

use App\Pipelines\Pipe;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class PricePipe extends Pipe
{
    protected function apply(Builder $query, mixed $value): void {}

    public function handle(Builder $query, Closure $next): Builder
    {
        if (request()->filled('price_min')) {
            $query->where('price', '>=', request('price_min'));
        }

        if (request()->filled('price_max')) {
            $query->where('price', '<=', request('price_max'));
        }

        return $next($query);
    }
}
