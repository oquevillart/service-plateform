<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HangmanController extends AbstractController
{
    #[Route('/hangman', name: 'app_hangman')]
    public function index(): Response
    {
        return $this->render('hangman/index.html.twig', [
            'controller_name' => 'HangmanController',
        ]);
    }

    #[Route('ajax/hangman/generate',methods:['get'] ,name: 'ajax_hangman_generate')]
    public function generate(EntityManagerInterface $em): JsonResponse
    {
        $word = "az'er ty ui-op";
        
        return new JsonResponse([
            'word' => $word
        ]);
    }
}
