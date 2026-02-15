<?php

namespace App\Controller\Admin;

use App\Entity\Profile;
use App\Form\ProfileFormType;
use App\Repository\ProfileRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProfileController extends AbstractController
{
    #[Route('/admin/profile', name: 'app_admin_profile')]
    public function index(ProfileRepository $profileRepository): RedirectResponse{
        $profile = $profileRepository->findOneBy([]);
        if(!empty($profile)){
            return $this->redirectToRoute('app_admin_profile_show',['id' => $profile->getId()]);
        }

        return $this->redirectToRoute('app_admin_profile_new');
    }

    #[Route('/admin/profile/{id<\d+>}',name:'app_admin_profile_show')]
    public function show(Profile $profile): Response{
        return $this->render('admin/profile/show.html.twig',[
            'profile' => $profile
        ]);
    }

    #[Route('/admin/profile/new',name:'app_admin_profile_new')]
    public function new(Request $request, EntityManagerInterface $emi): Response|RedirectResponse{
        
        $profile = new Profile();

        $form = $this->createForm(ProfileFormType::class,$profile);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $profile->setCreated(new \DateTime());

            try{
                $emi->persist($profile);
                $emi->flush();

                $this->addFlash('success', 'The profile has been successfully updated!');
                return $this->redirectToRoute('app_admin_profile_show',['id' => $profile->getId()]);
            } catch (UniqueConstraintViolationException $ex) {
                // LOG
            } catch (Exception $ex){
                // LOG
            } catch (\Throwable $ex){
                // LOG
            }
        }

        return $this->render('admin/profile/new.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/admin/profile/edit/{id<\d+>}',name:'app_admin_profile_edit')]
    public function edit(Profile $profile,Request $request, EntityManagerInterface $emi): Response{
        $form = $this->createForm(ProfileFormType::class,$profile);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $emi->flush();
                $this->addFlash('success', 'The profile has been successfully updated!');
                return $this->redirectToRoute('app_admin_profile_show',['id' => $profile->getId()]);
            } catch (UniqueConstraintViolationException $ex) {
                // LOG
            } catch (Exception $ex){
                // LOG
            } catch (\Throwable $ex){
                // LOG
            }
        }

        return $this->render('admin/profile/edit.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/admin/profile/delete/{id<\d+>}',name: 'app_admin_profile_delete', methods: ['POST'])]
    public function deleteProject(Request $request,Profile $profile, EntityManagerInterface $emi):RedirectResponse{
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $profile->getId(),$submittedToken)){
            $emi->remove($profile);
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

        return $this->redirectToRoute('app_admin_profile');
    }
}
