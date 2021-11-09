<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

final class TagsDataTransformer implements DataTransformerInterface
{
    public function __construct(private TagRepository $tagRepository)
    {
    }

    /**
     * @param Collection<int, Tag> $value
     */
    public function transform($value): string
    {
        return implode(',', $value->map(static fn (Tag $tag) => $tag->getName())->toArray());
    }

    /**
     * @param string $value
     *
     * @return Collection<int, Tag>
     */
    public function reverseTransform($value): Collection
    {
        $tags = explode(',', $value);
        array_walk($tags, static fn (string & $tag) => $tag = trim($tag));

        return new ArrayCollection(array_map([$this, 'getTag'], $tags));
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
