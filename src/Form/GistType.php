<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransformer\TagsDataTransformer;
use App\Entity\Gist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class GistType extends AbstractType
{
    public function __construct(private TagsDataTransformer $tagsDataTransformer)
    {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('url', UrlType::class)->add('tags', TextType::class);

        $builder->get('tags')->addModelTransformer($this->tagsDataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Gist::class);
    }
}
