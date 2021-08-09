<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\Type\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;
use Twig\Environment;


/**
 * Class MicroPostController
 * @package App\Controller
 */
class MicroPostController
{

    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * MicroPostController constructor.
     * @param Environment $twig
     * @param MicroPostRepository $microPostRepository
     */
    public function __construct(
        Environment $twig,
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManager $em,
        Router $router,
        FlashBagInterface $flashBag
    )
    {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }


    /**
     * @Route("/posts", name="micro_post")
     */
    public function index(): Response
    {

        $html = $this->twig->render('micro_post/index.html.twig', [
            'posts' => $this->microPostRepository->findAllMicroPostsOrderByNewest()
        ]);

        return new Response($html);

    }

    /**
     * @Route("/post/{id}", name="micro_post_view")
     * @param MicroPost $post
     * @return Response
     */
    public function post(MicroPost $post): Response
    {
        $html = $this->twig->render('micro_post/post.html.twig', ['post' => $post]);
        return new Response($html);
    }


    /**
     * @Route("post/delete/{id}", name="micro_post_delete")
     * @param MicroPost $post
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(MicroPost $post): Response
    {
        $this->em->remove($post);
        $this->em->flush();
        $this->flashBag->add('success', 'Post successfully deleted!');
        return new RedirectResponse($this->router->generate('micro_post'));
    }

    /**
     * @Route("/post/edit/{id}", name="micro_post_edit")
     * @param MicroPost $post
     * @return Response
     */
    public function edit(MicroPost $post, Request $request): Response
    {
        // build the form type
        $microPostForm = $this->formFactory->create(MicroPostType::class, $post);

        $microPostForm->handleRequest($request);

        if ($microPostForm->isSubmitted() && $microPostForm->isValid()) {
            $post = $microPostForm->getData();
            $this->em->persist($post);
            $this->em->flush();
            return new RedirectResponse($this->router->generate('micro_post'));
        }

        $html = $this->twig->render('micro_post/add.html.twig', ['form' => $microPostForm->createView()]);
        return new Response($html);
    }


    /**
     * @Route("/post/add", name="micro_post_add")
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add(Request $request): Response
    {
        $microPost = new MicroPost();
        $microPost->setTime(new \DateTime());
        $microPost->setText('This is some test text');

        $microPostForm = $this->formFactory->create(MicroPostType::class, $microPost);

        $microPostForm->handleRequest($request);
        if ($microPostForm->isSubmitted() && $microPostForm->isValid()) {
            $microPost = $microPostForm->getData();

            $this->em->persist($microPost);
            $this->em->flush();

            return new RedirectResponse($this->router->generate('micro_post'));
        }


        $html = $this->twig->render('micro_post/add.html.twig', [
            'form' => $microPostForm->createView()
        ]);

        return new Response($html);
    }
}
