<?php

namespace App\Controller;

use App\Entity\ShortenedUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UrlShortnerController extends AbstractController
{
    #[Route('/url-shortener', name: 'app_url_shortener')]
    public function index(): Response
    {
        

        return $this->render('url_shortener/index.html.twig', [
            'controller_name' => 'UrlShortnerController',
        ]);
    }

    #[Route('/u/{slug}', name: 'app_url_shortener_redirect')]
    public function redirecting(string $slug,
    EntityManagerInterface $em): Response
    {
        
        $url = $em->getRepository(ShortenedUrl::class)->findOneBy(['slug' => $slug]);

        return $this->redirect($url->getUrl());
    }

    #[Route('/ajax/url-shortener', methods:['post'],name: 'ajax_url_shortener')]
    public function urlShortner(Request $request,EntityManagerInterface $em): JsonResponse
    {
        $url = $request->request->get('url');
        if (null == $url) {
            return new JsonResponse([
                'error' => "Submitted empty field"
            ],500);
        }


        $url_validation_regex = "/^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/"; 
        if (true != preg_match($url_validation_regex, $url)) {
            return new JsonResponse([
                'error' => "Submitted URL need HTTP protocol"
            ],500);
        }

        $slug = uniqid();
        $newUrl = new ShortenedUrl();
        $newUrl->setSlug($slug);
        $newUrl->setUrl($url);
        $em->persist($newUrl);
        $em->flush();

        
        return new JsonResponse([
            'url' => $this->generateUrl('app_url_shortener_redirect', ['slug' => $slug], UrlGenerator::ABSOLUTE_URL)
        ]);
    }
}
