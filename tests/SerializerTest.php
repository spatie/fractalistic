<?php

use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Test\TestClasses\TestTransformer;
use function PHPUnit\Framework\assertEquals;

it('does not generate a data key for a collection', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->serializeWith(new ArraySerializer())
        ->toArray();

    $expectedArray = [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ];

    assertEquals($expectedArray, $array);
});

it('does not generate a data key for an item', function () {
    $array = $this->fractal
        ->item($this->testBooks[0], new TestTransformer())
        ->serializeWith(new ArraySerializer())
        ->toArray();

    $expectedArray = ['id' => 1, 'author' => 'Philip K Dick'];

    assertEquals($expectedArray, $array);
});

it('accepts a class name for the serializer', function () {
    $array = $this->fractal
        ->item($this->testBooks[0], new TestTransformer())
        ->serializeWith(ArraySerializer::class)
        ->toArray();

    $expectedArray = ['id' => 1, 'author' => 'Philip K Dick'];

    assertEquals($expectedArray, $array);
});
