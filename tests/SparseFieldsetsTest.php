<?php

namespace Spatie\Fractalistic\Test;

use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;

class SparseFieldsetsTest extends TestCase
{
    /** @test */
    public function it_can_filter_out_fields()
    {
        $array = Fractal::create()
            ->item([
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ], function ($item) {
              return $item;
          }, new ArraySerializer())
          ->withResourceName('test')
          ->parseFieldsets(['test' => 'key1,key3'])
          ->toArray();

        $expectedArray = [
            'data' => [
                'key1' => 'value1',
                'key3' => 'value3',
            ],
        ];

        $this->assertEquals($expectedArray, $array);
    }
}
