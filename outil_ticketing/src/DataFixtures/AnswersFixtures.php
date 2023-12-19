<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Answers;
use App\Entity\Tickets;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\TicketsFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnswersFixtures extends Fixture implements DependentFixtureInterface
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

    private function getCategoriesIds(ObjectManager $manager): array
    {
        // Récupérer tous les IDs des catégories
        $ticketsIds = $manager->getRepository(Tickets::class)->createQueryBuilder('c')
            ->select('c.id')
            ->getQuery()
            ->getScalarResult();

        // Transformer le résultat en un tableau simple d'IDs
        $ticketsIds = array_column( $ticketsIds, 'id');

        return  $ticketsIds;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR'); //récupération des id des utilisateurs
        $ticketsIds = $this->getCategoriesIds($manager); //récupération des id des tickets
        $userIds = $this->getUsersIds($manager);
        for($answ = 1; $answ<= 10; $answ++)
        {
            $answer = new Answers();
            $answer->setDescription($faker->text(500)); 
              //On va chercher un user. une caractéristique de faker est de nous permettre de créer des dépendances directement dans les data fixtures
              $userId = rand(1, 5);
              $user = $manager->getRepository(User::class)->find($userId);
            $answer->setUser($user);
            $manager->persist( $answer);

              // Associer un utilisateur au hasard
              $randomUserId = $userIds[array_rand($userIds)];
              $user = $manager->getRepository(User::class)->find($randomUserId);
              $answer->setUser($user);

              // Associer un ticket au hasard
            $randomticketsIds = $ticketsIds [array_rand($ticketsIds )];
            $ticket= $manager->getRepository(Tickets::class)->find( $randomticketsIds);
            $answer->setTicket($ticket);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TicketsFixtures::class
        ];  
    }
}
