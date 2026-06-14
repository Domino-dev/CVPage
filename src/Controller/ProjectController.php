<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectController extends AbstractController
{
    #[Route('/projekt/{slug}', name: 'app_project_show')]
    public function read(#[MapEntity(mapping: ['slug' => 'slug'])] Project $project): Response{
        return $this->render('project/show.html.twig', [
            'project' => $project
        ]);
    }
}
