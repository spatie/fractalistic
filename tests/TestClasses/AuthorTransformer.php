<?php

namespace Spatie\Fractalistic\Test\TestClasses;

use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'books',
    ];

    public function transform(Author $author): array
    {
        return [
            'name' => $author->name,
            'email' => $author->email,
        ];
    }

    public function includeBooks(Author $author): Collection
    {
        return $this->collection($author->books(), new BookTransformer());
    }
}
