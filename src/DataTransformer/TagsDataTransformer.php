<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

final class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * @param TagRepository<Tag> $tagRepository
     */
    public function __construct(private TagRepository $tagRepository)
    {
    }

    /**
     * @param array<int, Tag> $value
     */
    public function transform($value): string
    {
        return implode(',', array_map(static fn (Tag $tag) => $tag->getName(), $value));
    }

    /**
     * @param string $value
     *
     * @return array<int, Tag>
     */
    public function reverseTransform($value): array
    {
        $tags = explode(',', $value);
        array_walk($tags, static fn (string & $tag) => $tag = trim($tag));

        return array_map([$this, 'getTag'], $tags);
    }

    private function getTag(string $tagName): Tag
    {
        /** @var ?Tag $tag */
        $tag = $this->tagRepository->findOneBy(['name' => $tagName]);

        if (null === $tag) {
            $tag = new Tag();
            $tag->setName($tagName);
            $this->tagRepository->create($tag);
        }

        return $tag;
    }
}
