<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello', name: 'app_hello')]
    public function index(): Response
    {
        return $this->render('hello/index.html.twig');
    }

    #[Route('/hello/{name}/{times<\d+>}', name: 'app_hello_manytimes')]
    public function times(string $name, int $times = 3): Response
    {
        if (0 == $times or $times > 10) {
            return $this->redirect('http://localhost:8000/hello/bob/3');
        // return $this->render('hello/many_times.html.twig', ['name' => $name, 'times' => 3]);
        } else {
            return $this->render('hello/many_times.html.twig', ['name' => $name, 'times' => $times]);
        }
    }

    #[Route('/hello/{name}')]
    public function world(string $name): Response
    {
        // return new Response('Hello '.$name." !");
        return $this->render('hello/show.html.twig', ['name' => $name]);
    }
}
