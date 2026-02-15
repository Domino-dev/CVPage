<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\ProjectRepository;
use App\Service\ProjectService;
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

    #[Route('/admin/project/{id<\d+>}', name: 'app_admin_project_show')]
    public function read(Project $project): Response{
        return $this->render('admin/project/show.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/admin/project/new', name:'app_admin_project_new')]
    public function new(ProjectService $projectService, Request $request, EntityManagerInterface $emi): Response{

        $project = new Project();

        $form = $this->createForm(ProjectFormType::class,$project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $imagesUplaodDir = $this->getParameter('projects_uploads_directory');
            /** @var UploadedFile $image */
            $mImages = $form->get('mImage')->getData();
            $sImage = $form->get('sImage')->getData();

            $projectService->createImages($project,$mImages,$sImage,$imagesUplaodDir);

            $project->setCreated(new DateTime());

            $emi->persist($project);
            $emi->flush();

            $this->addFlash('success', 'Project has been successfully created!');

            return $this->redirectToRoute('app_admin_project_show',[
                'id' => $project->getId(),
            ]);
        }

        return $this->render('admin/project/new.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/project/edit/{id<\d+>}', name: 'app_admin_project_edit')]
    public function edit(ProjectService $projectService, Project $project, Request $request, EntityManagerInterface $emi){

        $oldSImage = $project->getSImage();
        $oldMImage = $project->getMImage();

        $form = $this->createForm(ProjectFormType::class,$project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $imagesUploadDir = $this->getParameter('projects_uploads_directory');
            /** @var UploadedFile $image */
            $mImages = $form->get('mImage')->getData();
            $sImage = $form->get('sImage')->getData();

            $projectService->deleteImages($oldSImage,$oldMImage,$imagesUploadDir);
            $projectService->createImages($project,$mImages,$sImage,$imagesUploadDir);

            $emi->flush();

            $this->addFlash('success', 'The project has been successfully updated!');

            return $this->redirectToRoute('app_admin_project_show',['id' => $project->getId()]);
        }

        return $this->render('/admin/project/edit.html.twig',[
            'form' => $form,
        ]);
    }

    #[Route('/admin/project/delete/{id<\d+>}',name: 'app_admin_project_delete', methods: ['POST'])]
    public function deleteProject(ProjectService $projectService, Request $request,Project $project, EntityManagerInterface $emi):RedirectResponse{
        $submittedToken = $request->request->get('_token');

        $imagesUploadDir = $this->getParameter('projects_uploads_directory');
        $mImages = $project->getMImage();
        $sImage = $project->getSImage();

        $projectService->deleteImages($mImages,$sImage,$imagesUploadDir);

        if ($this->isCsrfTokenValid('delete' . $project->getId(),$submittedToken)){
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
