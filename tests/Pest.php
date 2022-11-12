<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

use Spatie\Fractalistic\Fractal;

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
