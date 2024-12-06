<?php

namespace Spatie\Fractalistic\Test\TestClasses;

class Author
{
    public string $name;
    public string $email;
    /** @var Book[] */
    public ?array $books = [];

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function books(): array
    {
        return $this->books;
    }
}
