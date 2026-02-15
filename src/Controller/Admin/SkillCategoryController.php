<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Entity\SkillCategory;
use App\Form\SkillCategoryFormType;
use App\Repository\SkillCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class SkillCategoryController extends AbstractController
{
    #[Route('/admin/skill-category', name: 'app_admin_skill_category')]
    public function index(SkillCategoryRepository $skillCategoryRepository): Response
    {
        return $this->render('admin/skillCategory/index.html.twig', [
            'skillCategories' => $skillCategoryRepository->findAll()
        ]);
    }

    #[Route('/admin/skill-category/new', name:'app_admin_skill_category_new')]
    public function new(Request $request, EntityManagerInterface $emi): Response{

        $skillCategory = new SkillCategory();

        $skillCategory->addSkill(new Skill());

        $form = $this->createForm(SkillCategoryFormType::class,$skillCategory);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $skillCategory->setCreated(new \DateTime());

            $emi->persist($skillCategory);
            $emi->flush();

            $this->addFlash('success', 'SkillCategory has been successfully created!');

            return $this->redirectToRoute('app_admin_skill_category');
        }

        return $this->render('admin/skillCategory/new.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/skill-category/edit/{id<\d+>}', name: 'app_admin_skill_category_edit')]
    public function edit(SkillCategory $skillCategory, Request $request, EntityManagerInterface $emi){
        $form = $this->createForm(SkillCategoryFormType::class,$skillCategory);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $emi->flush();

            $this->addFlash('success', 'The skill category has been successfully updated!');

            return $this->redirectToRoute('app_admin_skill_category');
        }

        return $this->render('/admin/skillCategory/edit.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/skill-category/delete/{id<\d+>}',name: 'app_admin_skill_category_delete', methods: ['POST'])]
    public function deleteProject(Request $request,SkillCategory $skillCategory, EntityManagerInterface $emi):RedirectResponse{
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $skillCategory->getId(),$submittedToken)){
            $emi->remove($skillCategory);
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

        return $this->redirectToRoute('app_admin_skill_category');
    }
}
