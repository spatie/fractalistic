<?php

use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;
use function PHPUnit\Framework\assertEquals;

it('can filter out fields', function () {
    $array = Fractal::create()
        ->item([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
        ], function ($item) {
          return $item;
      }, 'test_name')
      ->withResourceName('test')
      ->parseFieldsets(['test' => 'key1,key3'])
      ->toArray();

    $expectedArray = [
        'data' => [
        'key1' => 'value1',
        'key3' => 'value3',
        ],
    ];

    assertEquals($expectedArray, $array);
});
