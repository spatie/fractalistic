<?php

use Spatie\Fractalistic\Test\TestTransformer;
use function PHPUnit\Framework\assertEquals;

it('can add meta', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->addMeta(['key' => 'value'])
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ],
        'meta' => ['key' => 'value'], ];

    assertEquals($expectedArray, $array);
});

it('can handle multiple meta', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->addMeta(['key1' => 'value1'], ['key2' => 'value2'])
        ->addMeta(['key3' => 'value3'])
        ->addMeta(['key4' => 'value4'])
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ],
        'meta' => [
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
        'key4' => 'value4',
        ], ];

    assertEquals($expectedArray, $array);
});
