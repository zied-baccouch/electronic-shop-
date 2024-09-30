<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class IndexController extends AbstractController
{
    #[Route("/", name: "article_list")]
    public function home(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    #[Route('/article/save', name: 'addArticle')]
    public function save(EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(1000);
        $em->persist($article);
        $em->flush();
        return new Response('Article enregistré avec id' . $article->getId());
    }

    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em->getRepository(Article::class);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_list');
        }
        return $this->render(
                 'articles/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show($id, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article/edit/{id}', name: 'edit_article', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $article = $em->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute("article_list");
        }
        return $this->render(
                 'articles/edit.html.twig', ["form" => $form->createView()]);
    }
    
    #[Route('/article/delete/{id}', name: 'delete_article', methods: ['GET'])]
    public function delete(Request $request, $id, EntityManagerInterface $em): Response
    {
        // Récupérer l'article par son ID
        $article = $em->getRepository(Article::class)->find($id);

        // Vérifier si l'article existe
        if (!$article) {
            // Si l'article n'existe pas, renvoyer une réponse appropriée
            throw $this->createNotFoundException('Article not found');
        }

        // Supprimer l'article
        $em->remove($article);
        $em->flush();

        // Rediriger vers la liste des articles après la suppression
        return $this->redirectToRoute('article_list');
    }
    
    #[Route('/categorie/new', name: 'new_categorie', methods: ['GET', 'POST'])]
    public function newCategorie(Request $req, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            $em->getRepository(Categorie::class);
            $em->persist($categorie);
            $em->flush();
    
            return $this->redirectToRoute('article_list');
        }
    
        return $this->render('articles/newCategorie.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
