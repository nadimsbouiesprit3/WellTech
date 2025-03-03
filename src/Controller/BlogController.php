<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\LikeDislikeRepository;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Entity\LikeDislike;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepo): Response
    {
        $articles = $articleRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function show(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Formulaire de commentaire
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setArticle($article);
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès !');
            return $this->redirectToRoute('app_blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig');
    }
    #[Route('/blog/{id}/like', name: 'app_blog_like', methods: ['POST'])]
public function like(Article $article, LikeDislikeRepository $likeDislikeRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
    }

    $existingReaction = $likeDislikeRepository->findOneBy(['user' => $user, 'article' => $article]);

    if ($existingReaction) {
        if ($existingReaction->getType() === 'like') {
            $entityManager->remove($existingReaction);
        } else {
            $existingReaction->setType('like');
        }
    } else {
        $newReaction = new LikeDislike();
        $newReaction->setUser($user);
        $newReaction->setArticle($article);
        $newReaction->setType('like');
        $entityManager->persist($newReaction);
    }

    $entityManager->flush();

    return new JsonResponse([
        'likes' => $likeDislikeRepository->countLikes($article->getId()),
        'dislikes' => $likeDislikeRepository->countDislikes($article->getId())
    ]);
}

#[Route('/blog/{id}/dislike', name: 'app_blog_dislike', methods: ['POST'])]
public function dislike(Article $article, LikeDislikeRepository $likeDislikeRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
    }

    $existingReaction = $likeDislikeRepository->findOneBy(['user' => $user, 'article' => $article]);

    if ($existingReaction) {
        if ($existingReaction->getType() === 'dislike') {
            $entityManager->remove($existingReaction);
        } else {
            $existingReaction->setType('dislike');
        }
    } else {
        $newReaction = new LikeDislike();
        $newReaction->setUser($user);
        $newReaction->setArticle($article);
        $newReaction->setType('dislike');
        $entityManager->persist($newReaction);
    }

    $entityManager->flush();

    return new JsonResponse([
        'likes' => $likeDislikeRepository->countLikes($article->getId()),
        'dislikes' => $likeDislikeRepository->countDislikes($article->getId())
    ]);
}

}
