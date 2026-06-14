<?php

namespace App\Service;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;

class SlugService
{
    private Slugify $slugify;

    public function __construct(
        private EntityManagerInterface $em,
    ) {
        $this->slugify = new Slugify();
    }

    public function generateUnique(string $text, string $entityClass, string $field = 'slug'): string
    {
        $base = $this->slugify->slugify($text);
        $slug = $base;
        $i = 1;

        $repo = $this->em->getRepository($entityClass);

        while ($repo->findOneBy([$field => $slug])) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}