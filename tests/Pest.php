<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

use Spatie\Fractalistic\Fractal;
use Spatie\Fractalistic\Test\TestClasses\Author;
use Spatie\Fractalistic\Test\TestClasses\Book;
use Spatie\Fractalistic\Test\TestClasses\Character;
use Spatie\Fractalistic\Test\TestClasses\Publisher;

uses()
    ->beforeEach(function () {
        $this->defaultSerializer = '';

        $this->fractal = Fractal::create();

        $this->testBooks = [
            [
                'id' => '1',
                'title' => 'Hogfather',
                'yr' => '1998',
                'author_name' => 'Philip K Dick',
                'author_email' => 'philip@example.org',
                'characters' => [['name' => 'Death'], ['name' => 'Hex']],
                'publisher' => 'Elephant books',
            ],
            [
                'id' => '2',
                'title' => 'Game Of Kill Everyone',
                'yr' => '2014',
                'author_name' => 'George R. R. Satan',
                'author_email' => 'george@example.org',
                'characters' => [['name' => 'Ned Stark'], ['name' => 'Tywin Lannister']],
                'publisher' => 'Bloody Fantasy inc.',
            ],
        ];
    })
    ->in('.');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/
function getTestPublishers(): array
{
    $authorA = new Author('Philip K Dick', 'philip@example.org');
    $authorB = new Author('George R. R. Satan', 'george@example.org');

    $charA = new Character('Death');
    $charB = new Character('Hex');
    $charC = new Character('Ned Stark');
    $charD = new Character('Tywin Lannister');

    $bookA = new Book(
        '1',
        'Hogfather',
        '1998',
    );
    $bookB = new Book(
        '2',
        'Game Of Kill Everyone',
        '2014',
    );

    $publisherA = new Publisher(
        'Elephant books',
        'Amazon rainforests, near the river',
    );
    $publisherB = new Publisher(
        'Bloody Fantasy inc.',
        'Diagon Alley, before the bank, to the left',
    );

    $bookA->author = $authorA;
    $bookA->publisher = $publisherA;
    $bookA->characters = [$charA, $charB];
    $authorA->characters = [$charA, $charB];
    $charA->book = $bookA;
    $charB->book = $bookA;
    $publisherA->books = [$bookA];
    $authorA->books = [$bookA];

    $bookB->author = $authorB;
    $bookB->publisher = $publisherB;
    $bookB->characters = [$charC, $charD];
    $authorB->characters = [$charC, $charD];
    $charC->book = $bookB;
    $charD->book = $bookB;
    $publisherB->books = [$bookB];
    $authorB->books = [$bookB];

    return [$publisherA, $publisherB];
}
