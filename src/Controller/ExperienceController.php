<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Form\ExperienceFormType;
use App\Repository\ExperienceRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ExperienceController extends AbstractController
{
    #[Route('/experience/{id<\d+>}', name: 'app_experience_show')]
    public function show(Experience $experience): Response{
        return $this->render('experience/show.html.twig', [
            'experience' => $experience
        ]);
    }
}
