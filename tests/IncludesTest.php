<?php

use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\Test\TestTransformer;
use function PHPUnit\Framework\assertEquals;

it('can parse includes', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->parseIncludes('characters')
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick',  'characters' => ['data' => [['name' => 'Death'], ['name' =>  'Hex']]]],
        ['id' => 2, 'author' => 'George R. R. Satan', 'characters' => ['data' => [['name' => 'Ned Stark'], ['name' => 'Tywin Lannister']]]],
    ]];

    assertEquals($expectedArray, $array);
});

it('provides a convenience method to include includes', function () {
    $resultWithParseIncludes = Fractal::create()
        ->collection($this->testBooks, new TestTransformer())
        ->parseIncludes('characters')
        ->toArray();

    $resultWithParseCharacters = Fractal::create()
        ->collection($this->testBooks, new TestTransformer())
        ->includeCharacters()
        ->toArray();

    assertEquals($resultWithParseIncludes, $resultWithParseCharacters);
});

it('can handle multiple includes', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->includeCharacters()
        ->includePublisher()
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick',  'characters' => ['data' => [['name' => 'Death'], ['name' =>  'Hex']]], 'publisher' => ['data' => ['Elephant books']]],
        ['id' => 2, 'author' => 'George R. R. Satan', 'characters' => ['data' => [['name' => 'Ned Stark'], ['name' => 'Tywin Lannister']]], 'publisher' => ['data' => ['Bloody Fantasy inc.']]],
    ]];

    assertEquals($expectedArray, $array);
});

it('can handle multiple includes at once', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->parseIncludes('characters, publisher')
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick',
        'characters' => ['data' => [['name' => 'Death'], ['name' =>  'Hex']]],
        'publisher' => ['data' => ['Elephant books']],
        ],
        ['id' => 2, 'author' => 'George R. R. Satan',
        'characters' => ['data' => [['name' => 'Ned Stark'], ['name' => 'Tywin Lannister']]],
        'publisher' => ['data' => ['Bloody Fantasy inc.']],
        ],
    ]];

    assertEquals($expectedArray, $array);
});

it('knows to ignore invalid includes param', function () {
    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->parseIncludes(null)
        ->toArray();

    $expectedArray = ['data' => [
        ['id' => 1, 'author' => 'Philip K Dick'],
        ['id' => 2, 'author' => 'George R. R. Satan'],
    ]];

    assertEquals($expectedArray, $array);

    $array = $this->fractal
        ->collection($this->testBooks, new TestTransformer())
        ->parseIncludes([])
        ->toArray();
    assertEquals($expectedArray, $array);
});
