<?php

namespace Spatie\Fractalistic\Test;

use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;

class FractalFunctionHelperTest extends TestCase
{
    /** @test */
    public function it_returns_an_instance_of_fractal_when_passing_no_arguments()
    {
        $this->assertInstanceOf(Fractal::class, Fractal::create());
    }

    /** @test */
    public function it_can_transform_the_given_array_with_the_given_closure()
    {
        $transformedData = Fractal::create(['item1', 'item2'], function ($item) {
            return ['item' => $item.'-transformed'];
        })->toArray();

        $this->assertEquals([
            'data' => [['item' => 'item1-transformed'], ['item' => 'item2-transformed']],
        ], $transformedData);
    }

    /** @test */
    public function it_can_transform_the_given_item_with_the_given_closure()
    {
        $item = new \stdClass();
        $item->name = 'item1';

        $transformedData = Fractal::create($item, function ($item) {
            return ["{$item->name}-transformed"];
        })->toArray();

        $this->assertEquals([
            'data' => ['item1-transformed'],
        ], $transformedData);
    }

    /** @test */
    public function it_can_transform_the_given_array_with_the_given_transformer()
    {
        $transformedData = Fractal::create($this->testBooks, new TestTransformer())->toArray();

        $expectedArray = ['data' => [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ]];

        $this->assertEquals($expectedArray, $transformedData);
    }

    /** @test */
    public function it_can_transform_the_given_empty_array_with_the_given_transformer()
    {
        $transformedData = Fractal::create([], new TestTransformer())->toArray();

        $expectedArray = ['data' => []];

        $this->assertEquals($expectedArray, $transformedData);
    }

    /** @test */
    public function it_can_transform_the_given_traversable_with_the_given_transformer()
    {
        $transformedData = Fractal::create(new TraversableClass($this->testBooks), new TestTransformer())->toArray();

        $expectedArray = ['data' => [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ]];

        $this->assertEquals($expectedArray, $transformedData);
    }

    /** @test */
    public function it_perform_a_transformation_with_the_given_serializer()
    {
        $transformedData = Fractal::create(
            $this->testBooks,
            new TestTransformer(),
            new ArraySerializer()
        )->toArray();

        $expectedArray = [
            ['id' => 1, 'author' => 'Philip K Dick'],
            ['id' => 2, 'author' => 'George R. R. Satan'],
        ];

        $this->assertEquals($expectedArray, $transformedData);
    }
}
