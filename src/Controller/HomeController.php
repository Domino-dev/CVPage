<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\ProfileRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillCategoryRepository;
use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ProfileRepository $profileRepository, 
        SkillCategoryRepository $skillCategoryRepository, 
        ProjectRepository $projectRepository,
        ExperienceRepository $experienceRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'profile' => $profileRepository->findOneBy([]),
            'skillCategories' => $skillCategoryRepository->findBy([],['position' => 'ASC']),
            'projects' => $projectRepository->findAll(),
            'experience' => $experienceRepository->findBy([],['dateFrom' => 'DESC'])
        ]);
    }
}
