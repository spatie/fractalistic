<?php

use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\Test\TestTransformerWithIncludes;
use function PHPUnit\Framework\assertEquals;

it('can parse excludes', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->parseExcludes('publisher')
        ->toArray();

    $expectedArray = [
        'data' => [
        ['id' => 1, 'author' => 'Philip K Dick', 'title' => ['data' => ['Hogfather']], 'characters' => ['data' => [['name' => 'Death'], ['name' => 'Hex']]]],
        ['id' => 2, 'author' => 'George R. R. Satan', 'title' => ['data' => ['Game Of Kill Everyone']], 'characters' => ['data' => [['name' => 'Ned Stark'], ['name' => 'Tywin Lannister']]]],
        ],
    ];

    assertEquals($expectedArray, $array);
});

it('provides a convenience method to exclude excludes', function () {
    $resultWithParseExcludes = Fractal::create()
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->parseExcludes('publisher')
        ->toArray();

    $resultWithParseExcludesAsaMethod = Fractal::create()
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->excludePublisher()
        ->toArray();

    assertEquals($resultWithParseExcludes, $resultWithParseExcludesAsaMethod);
});

it('can handle multiple excludes', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->excludePublisher()
        ->excludeCharacters()
        ->toArray();

    $expectedArray = [
        'data' => [
        ['id' => 1, 'author' => 'Philip K Dick', 'title' => ['data' => ['Hogfather']]],
        ['id' => 2, 'author' => 'George R. R. Satan', 'title' => ['data' => ['Game Of Kill Everyone']]],
        ],
    ];

    assertEquals($expectedArray, $array);
});

it('can handle multiple excludes at once', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->parseExcludes('characters, title')
        ->toArray();

    $expectedArray = [
        'data' => [
        ['id' => 1, 'author' => 'Philip K Dick', 'publisher' => ['data' => ['Elephant books']]],
        ['id' => 2, 'author' => 'George R. R. Satan', 'publisher' => ['data' => ['Bloody Fantasy inc.']]],
        ],
    ];

    assertEquals($expectedArray, $array);
});

it('knows to ignore invalid excludes param', function () {
    $expectedArray = [
        'data' => [
        ['id' => 1, 'author' => 'Philip K Dick', 'characters' => ['data' => [['name' => 'Death'], ['name' => 'Hex']]], 'publisher' => ['data' => ['Elephant books']], 'title' => ['data' => ['Hogfather']]],
        ['id' => 2, 'author' => 'George R. R. Satan', 'characters' => ['data' => [['name' => 'Ned Stark'], ['name' => 'Tywin Lannister']]], 'publisher' => ['data' => ['Bloody Fantasy inc.']], 'title' => ['data' => ['Game Of Kill Everyone']]],
        ],
    ];

    $excludeWhenPassedNull = $this->fractal
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->parseExcludes(null)
        ->toArray();

    assertEquals($expectedArray, $excludeWhenPassedNull);

    $excludeWhenPassedEmptyArray = $this->fractal
        ->collection($this->testBooks, new TestTransformerWithIncludes())
        ->parseExcludes([])
        ->toArray();

    assertEquals($expectedArray, $excludeWhenPassedEmptyArray);
});
