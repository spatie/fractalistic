<?php

namespace Spatie\Fractalistic\Test\TestClasses;

class Character
{
    public string $name;
    public ?Book $book = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function book(): ?Book
    {
        return $this->book;
    }
}
