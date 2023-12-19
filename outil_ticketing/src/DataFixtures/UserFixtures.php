<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UserFixtures extends Fixture
{
     //hacher le mdp
     public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    { $admin = new User();
        $admin->setEmail('admin@demo.fr');
        $admin->setLastName('Decobert');
        $admin->setFirstName('Thérèse');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, '758944') // '758944' est le mot de passe
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR'); //permet d'avoir d fausses données mais à la française

        for($usr = 1; $usr <= 5; $usr++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            );
            $manager->persist($user);
        }
        $manager->flush();
    }
}
