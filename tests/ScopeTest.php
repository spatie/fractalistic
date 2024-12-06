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

it('can access scope in transformer', function (): void {
    $fractal = Fractal::create(getTestPublishers(), new PublisherTransformer())
        ->parseIncludes('books.characters,books.author.characters');

    $result = $fractal->toArray();

    expect($result)->toEqual([
        'data' => [
            [
                'name' => 'Elephant books',
                'address' => 'Amazon rainforests, near the river',
                'books' =>
                    [
                        'data' => [
                            [
                                'id' => 1,
                                'title' => 'Hogfather',
                                'characters' => [
                                    'data' => [
                                        [
                                            'name' => 'Death',
                                            'current_scope' => 'characters',
                                            'parent_scope' => 'books',
                                            'scope_identifier' => 'books.characters',
                                            'called_by_book' => 'yes!',
                                        ],
                                        [
                                            'name' => 'Hex',
                                            'current_scope' => 'characters',
                                            'parent_scope' => 'books',
                                            'scope_identifier' => 'books.characters',
                                            'called_by_book' => 'yes!',
                                        ],
                                    ]
                                ],
                                'author' => [
                                    'data' => [
                                        'name' => 'Philip K Dick',
                                        'email' => 'philip@example.org',
                                        'characters' => [
                                            'data' => [
                                                [
                                                    'name' => 'Death',
                                                    'current_scope' => 'characters',
                                                    'parent_scope' => 'author',
                                                    'scope_identifier' => 'books.author.characters',
                                                    'called_by_author' => 'indeed!',
                                                ],
                                                [
                                                    'name' => 'Hex',
                                                    'current_scope' => 'characters',
                                                    'parent_scope' => 'author',
                                                    'scope_identifier' => 'books.author.characters',
                                                    'called_by_author' => 'indeed!',
                                                ],
                                            ]
                                        ],
                                    ]
                                ],
                            ],
                        ],
                    ],
            ],
            [
                'name' => 'Bloody Fantasy inc.',
                'address' => 'Diagon Alley, before the bank, to the left',
                'books' => [
                    'data' => [
                        [
                            'id' => 2,
                            'title' => 'Game Of Kill Everyone',
                            'characters' => [
                                'data' => [
                                    [
                                        'name' => 'Ned Stark',
                                        'current_scope' => 'characters',
                                        'parent_scope' => 'books',
                                        'scope_identifier' => 'books.characters',
                                        'called_by_book' => 'yes!',
                                    ],
                                    [
                                        'name' => 'Tywin Lannister',
                                        'current_scope' => 'characters',
                                        'parent_scope' => 'books',
                                        'scope_identifier' => 'books.characters',
                                        'called_by_book' => 'yes!',
                                    ],
                                ]
                            ],
                            'author' => [
                                'data' => [
                                    'name' => 'George R. R. Satan',
                                    'email' => 'george@example.org',
                                    'characters' => [
                                        'data' => [
                                            [
                                                'name' => 'Ned Stark',
                                                'current_scope' => 'characters',
                                                'parent_scope' => 'author',
                                                'scope_identifier' => 'books.author.characters',
                                                'called_by_author' => 'indeed!',
                                            ],
                                            [
                                                'name' => 'Tywin Lannister',
                                                'current_scope' => 'characters',
                                                'parent_scope' => 'author',
                                                'scope_identifier' => 'books.author.characters',
                                                'called_by_author' => 'indeed!',
                                            ],
                                        ]
                                    ],
                                ],
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ]);
});
