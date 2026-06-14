<?php

namespace App\Controller\Admin;

use App\Entity\Experience;
use App\Form\ExperienceFormType;
use App\Repository\ExperienceRepository;
use App\Service\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ExperienceController extends AbstractController
{

    #[Route('/admin/experience', name: 'app_admin_experience')]
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('admin/experience/index.html.twig', [
            'experience' => $experienceRepository->findAll(),
        ]);
    }

    #[Route('/admin/experience/{slug}', name: 'app_admin_experience_show')]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Experience $experience): Response{
        return $this->render('admin/experience/show.html.twig', [
            'experience' => $experience
        ]);
    }

    #[Route('/admin/experience/new', name:'app_admin_experience_new')]
    public function new(SlugService $slugService, Request $request, EntityManagerInterface $emi): Response{

        $experience = new Experience();

        $form = $this->createForm(ExperienceFormType::class,$experience);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $experience->setSlug($slugService->generateUnique($form->get('company')->getData(),Experience::class));

            $experience->setCreated(new \DateTime());

            $emi->persist($experience);
            $emi->flush();

            $this->addFlash('success', 'Experience has been successfully created!');

            return $this->redirectToRoute('app_admin_experience_show',[
                'slug' => $experience->getSlug(),
            ]);
        }


        return $this->render('admin/experience/new.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/experience/edit/{slug}', name: 'app_admin_experience_edit')]
    public function edit(#[MapEntity(mapping: ['slug' => 'slug'])] Experience $experience, SlugService $slugService, Request $request, EntityManagerInterface $emi){
        $form = $this->createForm(ExperienceFormType::class,$experience);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $experience->setSlug($slugService->generateUnique($form->get('company')->getData(),Experience::class));

            $emi->flush();

            $this->addFlash('success', 'The experience has been successfully updated!');

            return $this->redirectToRoute('app_admin_experience_show',['slug' => $experience->getSlug()]);
        }

        return $this->render('/admin/experience/edit.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/experience/delete/{slug}',name: 'app_admin_experience_delete', methods: ['POST'])]
    public function deleteProject(Request $request,#[MapEntity(mapping: ['slug' => 'slug'])] Experience $experience, EntityManagerInterface $emi):RedirectResponse{
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $experience->getSlug(),$submittedToken)){
            $emi->remove($experience);
            $emi->flush();

            $this->addFlash(
               'success',
               'Project has been delete!'
            );
        } else {
            $this->addFlash(
                'error',
                'Something went wrong!'
            );
        }

        return $this->redirectToRoute('app_admin_experience');
    }
}
