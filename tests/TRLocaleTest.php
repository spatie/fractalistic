<?php

namespace Spatie\Fractalistic\Test;


class TRLocaleTest extends TestCase
{
     /** @test */
    public function it_can_perform_a_single_item_with_tr_locale()
    {
        setlocale(LC_TIME, 'tr_TR.UTF-8');
        $array = $this->fractal
            ->item($this->testBooks[0], new TestTransformer())
            ->toArray();

        $expectedArray = ['data' => [
            'id' => 1, 'author' => 'Philip K Dick', ]];

        $this->assertEquals($expectedArray, $array);
    }
}
