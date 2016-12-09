<?php

namespace Spatie\Fractalistic\Test;

use PHPUnit_Framework_TestCase;
use Spatie\Fractalistic\Fractal;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /** @var \Spatie\Fractalistic\Fractal */
    protected $fractal;

    /** @var array */
    protected $testBooks;

    /** @var string|\League\Fractal\Serializer\SerializerAbstract */
    protected $defaultSerializer;

    public function setUp($defaultSerializer = '')
    {
        $this->defaultSerializer = $defaultSerializer;

        parent::setUp();

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
    }
}
