<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use App\Service\ProjectService;
use App\Service\SlugService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProjectController extends AbstractController
{
    #[Route('/admin/project', name: 'app_admin_project')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $projectRepository->findAll()
        ]);
    }

    #[Route('/admin/project/{slug}', name: 'app_admin_project_show')]
    public function read(#[MapEntity(mapping: ['slug' => 'slug'])] Project $project): Response{
        return $this->render('admin/project/show.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/admin/project/new', name:'app_admin_project_new')]
    public function new(ProjectService $projectService, SlugService $slugService, Request $request, EntityManagerInterface $emi): Response{

        $project = new Project();

        $form = $this->createForm(ProjectFormType::class,$project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $imagesUplaodDir = $this->getParameter('projects_uploads_directory');

            $project->setSlug($slugService->generateUnique($form->get('name')->getData(),Project::class));

            /** @var UploadedFile $image */
            $mImage = $form->get('mImage')->getData();
            $sImage = $form->get('sImage')->getData();

            $projectService->createImages($project,$mImage,$sImage,$imagesUplaodDir);

            $project->setCreated(new DateTime());

            $emi->persist($project);
            $emi->flush();

            $this->addFlash('success', 'Project has been successfully created!');

            return $this->redirectToRoute('app_admin_project_show',[
                'slug' => $project->getSlug(),
            ]);
        }

        return $this->render('admin/project/new.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/project/edit/{slug}', name: 'app_admin_project_edit')]
    public function edit(ProjectService $projectService, SlugService $slugService, #[MapEntity(mapping: ['slug' => 'slug'])] Project $project, Request $request, EntityManagerInterface $emi){

        $oldSImage = $project->getSImage();
        $oldMImage = $project->getMImage();

        $form = $this->createForm(ProjectFormType::class,$project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $imagesUploadDir = $this->getParameter('projects_uploads_directory');

            $project->setSlug($slugService->generateUnique($form->get('name')->getData(),Project::class));

            /** @var UploadedFile $image */
            $mImage = $form->get('mImage')->getData();
            $sImage = $form->get('sImage')->getData();
                 
            if ($mImage) {
                if ($oldMImage) {
                    $projectService->deleteImages($oldMImage, '', $imagesUploadDir);
                }
            } else {
                $project->setMImage($oldMImage);
            }

            if ($sImage) {
                if ($oldSImage) {
                    $projectService->deleteImages('', $oldSImage, $imagesUploadDir);
                }
            } else {
                $project->setSImage($oldSImage);
            }

            $projectService->createImages($project, $mImage, $sImage, $imagesUploadDir);

            $emi->flush();

            $this->addFlash('success', 'The project has been successfully updated!');

            return $this->redirectToRoute('app_admin_project_show',['slug' => $project->getSlug()]);
        }

        return $this->render('/admin/project/edit.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/project/delete/{slug}',name: 'app_admin_project_delete', methods: ['POST'])]
    public function deleteProject(ProjectService $projectService, Request $request,#[MapEntity(mapping: ['slug' => 'slug'])] Project $project, EntityManagerInterface $emi):RedirectResponse{
        $submittedToken = $request->request->get('_token');

        $imagesUploadDir = $this->getParameter('projects_uploads_directory');
        $mImage = $project->getMImage();
        $sImage = $project->getSImage();

        

        if ($this->isCsrfTokenValid('delete' . $project->getSlug(),$submittedToken)){

            $projectService->deleteImages($mImage,$sImage,$imagesUploadDir);

            $emi->remove($project);
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

        return $this->redirectToRoute('app_admin_project');
    }
}
