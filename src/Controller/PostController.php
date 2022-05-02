<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Form\PostType;


#[Route('/post', name: 'post.')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // return $this->render('post/index.html.twig', [
        //     'controller_name' => 'PostController',
        // ]);      
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();

            if($post->getId()!==null){
                $this->addflash(type:'success', message: 'Added post: ' . $post->getId() );
            }

            return $this->redirect($this->generateUrl(route:'post.list'));
        }

        return $this->renderForm('post/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/list.html.twig', [
            'posts' => $posts
        ]);
    }    

    #[Route('/show/{id}', name: 'show')]
    public function show(int $id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    } 

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, ManagerRegistry $managerRegistry, PostRepository $postRepository,): Response
    {
        $post = $postRepository->find($id);

        $em = $managerRegistry->getManager();

        $em->remove($post);
        $em->flush();

        $this->addFlash(type: 'danger', message: "El post " . $id . ", fue borrado");

        return $this->redirect($this->generateUrl(route: 'post.list'));
    }    

}