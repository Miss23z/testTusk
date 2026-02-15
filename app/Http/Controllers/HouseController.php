<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseSearchRequest;
use App\Models\House;
use App\Pipelines\House\BathroomsPipe;
use App\Pipelines\House\BedroomsPipe;
use App\Pipelines\House\GaragesPipe;
use App\Pipelines\House\NamePipe;
use App\Pipelines\House\PricePipe;
use App\Pipelines\House\StoreysPipe;
use Illuminate\Pipeline\Pipeline;

class HouseController extends Controller
{
    public function index(HouseSearchRequest $request)
    {
        return app(Pipeline::class)
            ->send(House::query())
            ->through([
                NamePipe::class,
                BedroomsPipe::class,
                BathroomsPipe::class,
                StoreysPipe::class,
                GaragesPipe::class,
                PricePipe::class,
            ])
            ->thenReturn()
            ->paginate(10);
    }
}
