<?php

namespace App\Controller;

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

final class SkillCategoryController extends AbstractController
{
    #[Route('/skill-category/edit/{id<\d+>}', name: 'app_skill_category_edit')]
    public function edit(SkillCategory $skillCategory, Request $request, EntityManagerInterface $emi){
        $form = $this->createForm(SkillCategoryFormType::class,$skillCategory);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $emi->flush();

            $this->addFlash('success', 'The skill category has been successfully updated!');

            return $this->redirectToRoute('app_admin_skill');
        }

        return $this->render('/skillCategory/edit.html.twig',[
            'form' => $form,
        ]);
    }
}
