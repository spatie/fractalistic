<?php

use Spatie\Fractalistic\ArraySerializer;
use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\Test\TestClasses\TestTransformer;
use Spatie\Fractalistic\Test\TestClasses\TraversableClass;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

it('returns an instance of fractal when passing no arguments', function () {
    assertInstanceOf(Fractal::class, Fractal::create());
});

it('can transform the given array with the given closure', function () {
    $transformedData = Fractal::create(['item1', 'item2'], function ($item) {
        return ['item' => $item.'-transformed'];
    })->toArray();

    assertEquals([
        'data' => [['item' => 'item1-transformed'], ['item' => 'item2-transformed']],
    ], $transformedData);
});

it('can transform the given item with the given closure', function () {
    $item = new \stdClass();
    $item->name = 'item1';

    $transformedData = Fractal::create($item, function ($item) {
        return ["{$item->name}-transformed"];
    })->toArray();

    assertEquals([
        'data' => ['item1-transformed'],
    ], $transformedData);
});

it('can transform the given array with the given transformer', function () {
    $transformedData = Fractal::create($this->testBooks, new TestTransformer())->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $transformedData);
});

it('can transform the given empty array with the given transformer', function () {
    $transformedData = Fractal::create([], new TestTransformer())->toArray();

    $expectedArray = ['data' => []];

    assertEquals($expectedArray, $transformedData);
});

it('can transform the given traversable with the given transformer', function () {
    $transformedData = Fractal::create(new TraversableClass($this->testBooks), new TestTransformer())->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $transformedData);
});

it('perform a transformation with the given serializer', function () {
    $transformedData = Fractal::create(
        $this->testBooks,
        new TestTransformer(),
        new ArraySerializer()
    )->toArray();

    $expectedArray = [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ];

    assertEquals($expectedArray, $transformedData);
});
