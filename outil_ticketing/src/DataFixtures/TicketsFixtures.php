<?php

namespace App\DataFixtures;

use App\Entity\Tickets;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class TicketsFixtures extends Fixture implements DependentFixtureInterface
{

    private function getUsersIds(ObjectManager $manager): array
    {
        // Récupérer tous les IDs des utilisateurs
        $userIds = $manager->getRepository(User::class)->createQueryBuilder('u')
            ->select('u.id')
            ->getQuery()
            ->getScalarResult();

        // Transformer le résultat en un tableau simple d'IDs
        $userIds = array_column($userIds, 'id');

        return $userIds;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
       
 // Récupérer les IDs des statuts, des utilisateurs, des catégories, des technologies
 $userIds = $this->getUsersIds($manager);

        for($tick = 1; $tick<= 10; $tick++)
        {
            $ticket = new Tickets();
            $statuts = ['ouvert', 'fermé'];
            $statutAleatoire = $faker->randomElement($statuts);
            $ticket->setStatus($statutAleatoire);
            $ticket->setTitle($faker->text(100)); // indiquer le nombre de caractères voulus. par défaut c'est 200
            $ticket->setContent($faker->text(500)); 
              //On va chercher un user. une caractéristique de faker est de nous permettre de créer des dépendances directement dans les data fixtures
            $userId = rand(1, 5);
            $user = $manager->getRepository(User::class)->find($userId);
            $ticket->setUser($user);
            $manager->persist($ticket);



            // Associer un utilisateur au hasard
            $randomUserId = $userIds[array_rand($userIds)];
            $user = $manager->getRepository(User::class)->find($randomUserId);
            $ticket->setUser($user);
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];  
    }
}
