<?php

namespace Spatie\Fractalistic;

use JsonSerializable;
use League\Fractal\Manager;
use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Scope;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;
use Spatie\Fractalistic\Exceptions\InvalidTransformation;
use Spatie\Fractalistic\Exceptions\NoTransformerSpecified;
use Traversable;

class Fractal implements JsonSerializable
{
    protected Manager $manager;

    protected int $recursionLimit = 10;

    protected string|SerializerAbstract|null $serializer;

    /** @var string|callable|TransformerAbstract */
    protected $transformer;

    protected ?PaginatorInterface $paginator = null;

    protected ?CursorInterface $cursor = null;

    protected array $includes = [];

    protected array $excludes = [];

    protected array $fieldsets = [];

    protected string $dataType;

    protected mixed $data;

    protected ?string $resourceName = null;

    protected array $meta = [];

    /**
     * @param mixed|null $data
     * @param callable|string|TransformerAbstract|null $transformer
     * @param string|SerializerAbstract|null $serializer
     *
     * @return Fractal
     */
    public static function create(
        mixed $data = null,
        callable|string|TransformerAbstract $transformer = null,
        SerializerAbstract|string|null $serializer = null
    ): Fractal {
        $instance = new static(new Manager());

        $instance->data = $data;
        $instance->dataType = $instance->determineDataType($data);
        $instance->transformer = $transformer ?: null;
        $instance->serializer = $serializer ?: null;

        return $instance;
    }

    /** @param Manager $manager */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Set the collection data that must be transformed.
     *
     * @param mixed $data
     * @param callable|string|TransformerAbstract|null $transformer
     * @param string|null $resourceName
     *
     * @return $this
     */
    public function collection(
        mixed $data,
        callable|string|TransformerAbstract $transformer = null,
        ?string $resourceName = null
    ): self {
        if (! is_null($resourceName)) {
            $this->resourceName = $resourceName;
        }

        return $this->data('collection', $data, $transformer);
    }

    /**
     * Set the item data that must be transformed.
     *
     * @param mixed $data
     * @param callable|string|TransformerAbstract|null $transformer
     * @param string|null $resourceName
     *
     * @return $this
     */
    public function item(
        mixed $data,
        callable|string|TransformerAbstract $transformer = null,
        ?string $resourceName = null
    ): self {
        if (! is_null($resourceName)) {
            $this->resourceName = $resourceName;
        }

        return $this->data('item', $data, $transformer);
    }

    /**
     * Set the primitive data that must be transformed.
     *
     * @param mixed $data
     * @param callable|string|TransformerAbstract|null $transformer
     * @param string|null $resourceName
     *
     * @return $this
     */
    public function primitive(
        mixed $data,
        callable|string|TransformerAbstract $transformer = null,
        ?string $resourceName = null
    ): self {
        if (! is_null($resourceName)) {
            $this->resourceName = $resourceName;
        }

        return $this->data('primitive', $data, $transformer);
    }

    /**
     * Set the data that must be transformed.
     *
     * @param string $dataType
     * @param mixed $data
     * @param callable|string|TransformerAbstract|null $transformer
     *
     * @return $this
     */
    public function data(string $dataType, mixed $data, callable|string|TransformerAbstract $transformer = null): self
    {
        $this->dataType = $dataType;

        $this->data = $data;

        if (! is_null($transformer)) {
            $this->transformer = $transformer;
        }

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function determineDataType(mixed $data): string
    {
        if (is_null($data)) {
            return 'NullResource';
        }

        if (is_array($data)) {
            return 'collection';
        }

        if ($data instanceof Traversable) {
            return 'collection';
        }

        return 'item';
    }

    /**
     * Set the class or function that will perform the transform.
     *
     * @param callable|string|TransformerAbstract $transformer
     *
     * @return $this
     */
    public function transformWith(callable|string|TransformerAbstract $transformer): self
    {
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * Set the serializer to be used.
     *
     * @param SerializerAbstract|string $serializer
     *
     * @return $this
     */
    public function serializeWith(SerializerAbstract|string $serializer): self
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Set a Fractal paginator for the data.
     *
     * @param PaginatorInterface $paginator
     *
     * @return $this
     */
    public function paginateWith(PaginatorInterface $paginator): self
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Set a Fractal cursor for the data.
     *
     * @param CursorInterface $cursor
     *
     * @return $this
     */
    public function withCursor(CursorInterface $cursor): self
    {
        $this->cursor = $cursor;

        return $this;
    }

    /**
     * Specify the includes.
     *
     * @param array|string|null $includes Array or string of resources to include.
     *
     * @return $this
     */
    public function parseIncludes(array|string|null $includes): self
    {
        $includes = $this->normalizeIncludesOrExcludes($includes);

        $this->includes = array_merge($this->includes, (array) $includes);

        return $this;
    }

    /**
     * Specify the excludes.
     *
     * @param array|string|null $excludes Array or string of resources to exclude.
     *
     * @return $this
     */
    public function parseExcludes(array|string|null $excludes): self
    {
        $excludes = $this->normalizeIncludesOrExcludes($excludes);

        $this->excludes = array_merge($this->excludes, (array) $excludes);

        return $this;
    }

    /**
     * Specify the fieldsets to include in the response.
     *
     * @param array $fieldsets array with key = resourceName and value = fields to include
     *                                (array or comma separated string with field names)
     *
     * @return $this
     */
    public function parseFieldsets(array $fieldsets): self
    {
        foreach ($fieldsets as $key => $fields) {
            if (is_array($fields)) {
                $fieldsets[$key] = implode(',', $fields);
            }
        }

        $this->fieldsets = array_merge($this->fieldsets, $fieldsets);

        return $this;
    }

    /**
     * Normalize the includes an excludes.
     *
     * @param array|string|null $includesOrExcludes
     *
     * @return array|string
     */
    protected function normalizeIncludesOrExcludes(array|string|null $includesOrExcludes = ''): array|string
    {
        if (! is_string($includesOrExcludes)) {
            return $includesOrExcludes ?? '';
        }

        return array_map(function ($value) {
            return trim($value);
        }, explode(',', $includesOrExcludes));
    }

    /**
     * Set the meta data.
     *
     * @return $this
     */
    public function addMeta(): self
    {
        foreach (func_get_args() as $meta) {
            if (is_array($meta)) {
                $this->meta += $meta;
            }
        }

        return $this;
    }

    /**
     * Set the resource name, to replace 'data' as the root of the collection or item.
     *
     * @param string $resourceName
     *
     * @return $this
     */
    public function withResourceName(string $resourceName): self
    {
        $this->resourceName = $resourceName;

        return $this;
    }

    /**
     * Upper limit to how many levels of included data are allowed.
     *
     * @param int $recursionLimit
     *
     * @return $this
     */
    public function limitRecursion(int $recursionLimit): self
    {
        $this->recursionLimit = $recursionLimit;

        return $this;
    }

    /**
     * Perform the transformation to json.
     *
     * @param int $options
     *
     * @return string
     * @throws InvalidTransformation
     * @throws NoTransformerSpecified
     */
    public function toJson(int $options = 0): string
    {
        return $this->createData()->toJson($options);
    }

    /**
     * Perform the transformation to array.
     *
     * @return array|null
     */
    public function toArray(): ?array
    {
        return $this->createData()->toArray();
    }

    /**
     * Create fractal data.
     *
     * @return Scope
     *
     * @throws \Spatie\Fractalistic\Exceptions\InvalidTransformation
     * @throws \Spatie\Fractalistic\Exceptions\NoTransformerSpecified
     */
    public function createData(): Scope
    {
        if (is_null($this->transformer)) {
            throw new NoTransformerSpecified();
        }

        if (is_string($this->serializer)) {
            $this->serializer = new $this->serializer;
        }

        if (! is_null($this->serializer)) {
            $this->manager->setSerializer($this->serializer);
        }

        $this->manager->setRecursionLimit($this->recursionLimit);

        if (! empty($this->includes)) {
            $this->manager->parseIncludes($this->includes);
        }

        if (! empty($this->excludes)) {
            $this->manager->parseExcludes($this->excludes);
        }

        if (! empty($this->fieldsets)) {
            $this->manager->parseFieldsets($this->fieldsets);
        }

        return $this->manager->createData($this->getResource(), $this->resourceName);
    }

    /**
     * Get the resource.
     *
     * @return ResourceInterface
     *
     * @throws \Spatie\Fractalistic\Exceptions\InvalidTransformation
     */
    public function getResource(): ResourceInterface
    {
        $resourceClass = 'League\\Fractal\\Resource\\'.ucfirst($this->dataType);

        if (! class_exists($resourceClass)) {
            throw new InvalidTransformation();
        }

        if (is_string($this->transformer)) {
            $this->transformer = new $this->transformer;
        }

        $resource = new $resourceClass($this->data, $this->transformer, $this->resourceName);

        $resource->setMeta($this->meta);

        if (! is_null($this->paginator)) {
            $resource->setPaginator($this->paginator);
        }

        if (! is_null($this->cursor)) {
            $resource->setCursor($this->cursor);
        }

        return $resource;
    }

    /**
     * Return the name of the resource.
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array|null
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize(): ?array
    {
        return $this->toArray();
    }

    /**
     * Support for magic methods to included data.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return $this
     */
    public function __call(string $name, array $arguments): self
    {
        if ($this->startsWith($name, ['include'])) {
            $includeName = lcfirst(substr($name, strlen('include')));

            return $this->parseIncludes($includeName);
        }

        if ($this->startsWith($name, ['exclude'])) {
            $excludeName = lcfirst(substr($name, strlen('exclude')));

            return $this->parseExcludes($excludeName);
        }

        trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param array|string $needles
     *
     * @return bool
     */
    protected function startsWith(string $haystack, array|string $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }
}
