<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncorder;


    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncorder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncorder)
    {
        $this->passwordEncorder = $passwordEncorder;
    }


    public function load(ObjectManager $manager)
    {
        $this->loadPosts($manager);
        $this->loadUsers($manager);
    }

    public function loadPosts(ObjectManager $manager) {
        for ($i = 0; $i < 10; $i++) {
            $post = new MicroPost();
            $post->setText("This is a new post created by a fixture $i");
            $post->setTime(new \DateTime());
            $manager->persist($post);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setFullname('Matthew De Jager');
        $user->setEmail('matt.dj@soapmedia.co.uk');
        $user->setUsername('matt');
        $user->setPassword($this->passwordEncorder->encodePassword($user, 'Polarbear1'));
        $manager->persist($user);
        $manager->flush();
    }
}
