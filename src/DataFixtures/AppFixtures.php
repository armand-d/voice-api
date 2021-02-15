<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    const DEFAULT_USER = [
        'email' => 'armand@gmail.com',
        'username' => 'armand@gmail.com',
        'password' => 'demo',
        'firstName' => 'Armand',
        'lastName' => 'Fourtané',
        'accountName' => 'SP',
        'role' => ["ROLE_USER"]
    ];

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $defaultUser = new User();

        $passHash = $this->passwordEncoder->encodePassword($defaultUser, SELF::DEFAULT_USER['password']);

        $defaultUser->setEmail(SELF::DEFAULT_USER['email'])
                    ->setPassword($passHash);

        $manager->persist($defaultUser);

        for ($u=0; $u < 10; $u++) {
            $user = new User();

            $passHash = $this->passwordEncoder->encodePassword($user, 'password');

            $user->setEmail($faker->email)
                ->setPassword($passHash)
                ->setFirstName($faker->name)
                ->setLastName($faker->name)
                ->setAccountName($faker->name)
                ->setLang('fr')
                ->setStatus('enabled');

            $manager->persist($user);

            for ($a=0; $a < random_int(0, 10); $a++) {
                $post = (new Post())->setAuthor($user)
                    ->setTitle($faker->text(30))
                    ->setImgPath('../../img-%d.png', $a)
                    ->setSoundPath('../../sound-%d.wov', $a)
                    ->setLang('fr')
                    ->setStatus('published');

                $manager->persist($post);

                for ($c=0; $c < random_int(0, 15); $c++) {
                    $comment = (new Comment())
                        ->setPost($post)
                        ->setAuthor($user)
                        ->setSoundPath('../../sound-%d.wov', $a)
                        ->setLang('fr')
                        ->setStatus('published');

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();

    }
}
