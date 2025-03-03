<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\LikeDislike;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/like-dislike')]
class LikeDislikeController extends AbstractController
{
    #[Route('/{id}/{action}', name: 'like_dislike', methods: ['POST'])]
    public function likeDislike(Article $article, string $action, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $userIdentifier = $this->getUser()->getUserIdentifier(); // Récupérer l'utilisateur

        // Vérifier si l'utilisateur a déjà liké/disliké cet article
        $existing = $entityManager->getRepository(LikeDislike::class)->findOneBy([
            'article' => $article,
            'userIdentifier' => $userIdentifier
        ]);

        if ($existing) {
            $entityManager->remove($existing);
        }

        if ($action === 'like') {
            $likeDislike = (new LikeDislike())
                ->setArticle($article)
                ->setIsLike(true)
                ->setUserIdentifier($userIdentifier);
            $entityManager->persist($likeDislike);
        } elseif ($action === 'dislike') {
            $likeDislike = (new LikeDislike())
                ->setArticle($article)
                ->setIsLike(false)
                ->setUserIdentifier($userIdentifier);
            $entityManager->persist($likeDislike);
        }

        $entityManager->flush();

        return new JsonResponse([
            'likes' => $article->getLikesCount(),
            'dislikes' => $article->getDislikesCount(),
        ]);
    }
}
