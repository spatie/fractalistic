<?php

use Spatie\Fractalistic\Exceptions\NoTransformerSpecified;

it('throws an exception if item or collection was not called', function () {
    $this->fractal->toJson();
})->throws(NoTransformerSpecified::class);

it('throws an exception if no transformer was specified', function () {
    $this->fractal->collection($this->testBooks)->toJson();
})->throws(NoTransformerSpecified::class);
