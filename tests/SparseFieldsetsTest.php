<?php

namespace Spatie\Fractalistic\Test;

use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\ArraySerializer;

class SparseFieldsetsTest extends TestCase
{
  /** @test */
  public function it_can_filter_out_fields()
  {
    $array = Fractal::create()
      ->item([
        'a' => 'A',
        'b' => 'B',
        'c' => 'C',
      ], function ($item) {
        return $item;
      }, new ArraySerializer())
      ->withResourceName('test')
      ->parseFieldsets(['test' => 'a,c'])
      ->toArray();

    $expectedArray = [
      'data' => [
        'a' => 'A',
        'c' => 'C',
      ],
    ];

    $this->assertEquals($expectedArray, $array);
  }
}
