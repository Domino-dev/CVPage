<?php

namespace App\Controller;

use App\Entity\Experience;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExperienceController extends AbstractController
{
    #[Route('/zkusenost/{slug}', name: 'app_experience_show')]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Experience $experience): Response{
        return $this->render('experience/show.html.twig', [
            'experience' => $experience
        ]);
    }
}
