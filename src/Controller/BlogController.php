<?php

namespace App\Controller;

use App\Service\Greeting;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Date;
use Twig\Environment;


/**
 * @Route("/blog")
 */
class BlogController
{


    /**
     * @var Greeting
     */
    protected $greeting;


    /**
     * @var Environment
     */
    protected $twig_env;


    /**
     * @var Session
     */
    protected $session;

    protected $router;

    /**
     * BlogController constructor.
     * @param Greeting $greeting
     */
    public function __construct(Greeting $greeting, Environment $twig_env, Session $session, RouterInterface $router)
    {
        $this->greeting = $greeting;
        $this->twig_env = $twig_env;
        $this->session = $session;
        $this->router = $router;
    }


    /**
     * @Route("/", name="blog_list")
     */
    public function index()
    {

        $posts = $this->session->get('posts');

        $html = $this->twig_env->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);

        return new Response($html);

    }

    /**
     * @Route("/add", name="blog_add")
     */
    public function add()
    {

        $posts = $this->session->get('posts');
        $id = uniqid();
        $posts[$id] = [
            'title' => "Blog post title $id",
            'desc' => "This is the blog desc $id",
            'date' =>  date("Y-m-d")
        ];

        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('blog_list'));

    }

    /**
     * @Route("/post/{id}", name="blog_post")
     */
    public function post($id)
    {
        $posts = $this->session->get('posts');

        if (empty($posts) || !array_key_exists($id, $posts)){
            throw new NotFoundHttpException('Post does not exist');
        }

        $html = $this->twig_env->render('blog/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id],
        ]);

        return new Response($html);
    }


}