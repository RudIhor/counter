<?php

declare(strict_types=1);

namespace Ihorrud\Counter\ValueObjects;

use Webmozart\Assert\Assert;

final class Tag
{
    private string $tag;

    private function __construct(string $tag)
    {
        Assert::string($tag);
        Assert::minLength($tag, 3);
        Assert::maxLength($tag, 50);

        $this->tag = $tag;
    }

    public static function fromString(string $tag): Tag
    {
        return new Tag($tag);
    }

    public function tag(): string
    {
        return $this->tag;
    }
}
