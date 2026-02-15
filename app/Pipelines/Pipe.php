<?php

namespace App\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Pipe
{
    abstract protected function apply(Builder $query, mixed $value): void;

    protected function key(): string
    {
        return Str::snake(
            Str::beforeLast(class_basename($this), 'Pipe')
        );
    }

    public function handle(Builder $query, Closure $next): Builder
    {
        if (request()->filled($this->key())) {
            $this->apply($query, request($this->key()));
        }

        return $next($query);
    }
}
