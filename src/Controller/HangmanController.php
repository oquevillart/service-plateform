<?php

namespace App\Controller;

use App\Entity\Word;
use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HangmanController extends AbstractController
{
    #[Route('/hangman', name: 'app_hangman')]
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('hangman/index.html.twig', []);
    }

    #[Route('ajax/hangman/generate', methods: ['get'], name: 'ajax_hangman_generate')]
    public function generate(EntityManagerInterface $em, WordRepository $repository): JsonResponse
    {
        $subQuery = $em->createQuery(
            'SELECT MIN(w.id), MAX(w.id) FROM App\\Entity\\Word w'
        )->getSingleResult();

        $minId = $subQuery[1];
        $maxId = $subQuery[2];

        $randomIds = array();

        $n = 1;

        for ($i = 0; $i < $n; $i++) {
            $randomIds[] = rand($minId, $maxId);
        }

        $query = $repository->createQueryBuilder('w')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', $randomIds)
            ->getQuery();

        $randomWord = $query->getOneOrNullResult();


        if ($randomWord == null) {
            return new JsonResponse([
                'word' => "néant"
            ]);
        }

        return new JsonResponse([
            'word' => $randomWord->getWord()
        ]);
    }

    #[Route('ajax/hangman/generate/new', methods: ['post'], name: 'ajax_hangman_generate_new')]
    public function generateNew(Request $request, EntityManagerInterface $em, WordRepository $repository): JsonResponse
    {

        $oldWords = json_decode($request->getContent());

        $subQuery = $em->createQuery(
            'SELECT MIN(w.id), MAX(w.id) FROM App\\Entity\\Word w'
        )->getSingleResult();

        $minId = $subQuery[1];
        $maxId = $subQuery[2];

        $randomIds = array();

        $n = 1;

        for ($i = 0; $i < $n; $i++) {
            $randomIds[] = rand($minId, $maxId);
        }

        if (null == $oldWords || empty($oldWords)) {

            $query = $repository->createQueryBuilder('w')
                ->where('w.id IN (:ids)')
                ->setParameter('ids', $randomIds)
                ->getQuery();

            $randomWord = $query->getOneOrNullResult();

            return new JsonResponse([
                'word' => $randomWord->getWord()
            ]);
        }
        $query = $repository->createQueryBuilder('w')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', $randomIds)
            ->andWhere('w.word NOT IN (:words)')
            ->setParameter('words', $oldWords)
            ->getQuery();

        $randomWord = $query->getOneOrNullResult();

        if ($randomWord == null) {
            return new JsonResponse([
                'word' => "néant"
            ]);
        }

        return new JsonResponse([
            'word' => $randomWord->getWord()
        ]);
    }
}
