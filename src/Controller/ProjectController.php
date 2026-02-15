<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine as GdImagine;
use Imagine\Image\Box;
use Imagine\Imagick\Imagine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectController extends AbstractController
{
    #[Route('/project/{id<\d+>}', name: 'app_project_show')]
    public function read(Project $project): Response{
        return $this->render('project/show.html.twig', [
            'project' => $project
        ]);
    }
}
