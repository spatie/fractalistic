<?php

namespace Spatie\Fractalistic\Test\TestClasses;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'publisher',
        'characters',
        'author',
    ];

    public function transform(Book $book): array
    {
        return [
            'id' => (int)$book->id,
            'title' => $book->title,
        ];
    }

    public function includePublisher(Book $book): Item
    {
        return $this->item($book->publisher(), new PublisherTransformer());
    }

    public function includeCharacters(Book $book): Collection
    {
        return $this->collection($book->characters(), new CharacterTransformer());
    }

    public function includeAuthor(Book $book): Item
    {
        return $this->item($book->author(), new AuthorTransformer());
    }
}
