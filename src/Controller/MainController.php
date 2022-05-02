<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main/{user?}', name: 'app_main')]
    public function index(Request $request): Response
    {
        // dump($request);
        // dump($request->get('user'));
        $user = $request->get('user');
        // return $this->json([
        //     'message' => 'Welcome to your new controller! ' . $user . '! ',
        //     'path' => 'src/Controller/MainController.php',
        // ]);
        return $this->render('home/custom.html.twig',[
            'user' => $user
        ]);
    }
}
