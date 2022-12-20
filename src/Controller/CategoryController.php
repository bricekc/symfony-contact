<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        // $categories = $repository->findBy([], ['name' => 'ASC']);
        $categories = $repository->createQueryBuilder('c')
            ->select('c as category')
            ->addSelect('COUNT(contacts) as count')
            ->leftJoin('c.contacts', 'contacts')
            ->groupBy('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    #[Route('/category/{id<\d+>}', name: 'app_category_id')]
    public function show(Category $category): Response
    {
        return $this->render('/category/show.html.twig', ['category' => $category]);
    }
}
