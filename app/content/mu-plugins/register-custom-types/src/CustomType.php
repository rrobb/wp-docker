<?php

declare(strict_types=1);

namespace RegisterCustomTypes;

abstract class CustomType implements CustomTypesInterface
{
    protected $args = [];
    protected $slug;
    protected $singular;
    protected $plural;

    final public function __construct(
        string $slug,
        string $singular,
        string $plural
    ) {
        $this->slug = $slug;
        $this->singular = $singular;
        $this->plural = $plural;
    }

    final public function setArgs(array $args): self
    {
        $this->args = $args;

        return $this;
    }

    final public function addArgs(array $args): self
    {
        $this->args = array_merge_recursive(
            $this->args,
            $args
        );

        return $this;
    }
}