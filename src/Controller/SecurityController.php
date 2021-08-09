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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Date;
use Twig\Environment;


class SecurityController
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
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        $html = $this->twig_env->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);

        return new Response($html);
    }




}