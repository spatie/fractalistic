<?php

use League\Fractal\ParamBag;
use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\Test\TestClasses\PublisherTransformer;

it('can parse include parameters', function ($resourceName, string $include, string $includeWithParams, ParamBag $expected): void {
    $fractal = Fractal::create(getTestPublishers(), new PublisherTransformer())
        ->withResourceName($resourceName)
        ->parseIncludes($includeWithParams);

    $scope = $fractal->createData();

    $identifier = $scope->getIdentifier($include);
    $actualParams = $scope->getManager()->getIncludeParams($identifier);
    expect($actualParams)->toEqual($expected);
})->with([
    [
        'resource name: string' => 'Publisher',
    ],
    [
        'resource name: null' => null,
    ],
])->with([
    [
        'include' => 'books',
        'include_with_params' => 'books:test(2|value)',
        'expected' => new ParamBag([
            'test' => ['2', 'value'],
        ]),
    ],
    [
        'include' => 'books',
        'include_with_params' => 'books:test(another_value|3):another(1|2|3)',
        'expected' => new ParamBag([
            'test' => ['another_value', '3'],
            'another' => ['1', '2', '3'],
        ]),
    ],
    [
        'include' => 'books.author',
        'include_with_params' => 'books.author:test(test|value)',
        'expected' => new ParamBag([
            'test' => ['test', 'value'],
        ]),
    ],
]);
