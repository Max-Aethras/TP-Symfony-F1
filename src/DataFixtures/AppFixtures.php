<?php
namespace App\DataFixtures;

use App\Entity\Ecurie;
use App\Entity\Pilote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ecuriesData = [
            ['name' => 'Scuderia Red', 'moteur' => 'Ferrari'],
            ['name' => 'Blue Racing', 'moteur' => 'Mercedes'],
            ['name' => 'Green Motors', 'moteur' => 'Renault'],
        ];

        foreach ($ecuriesData as $i => $e) {
            $ecurie = new Ecurie();
            $ecurie->setNom($e['name'])->setMoteur($e['moteur']);
            $manager->persist($ecurie);

            // 3 pilotes par écurie
            for ($j = 1; $j <= 3; $j++) {
                $pilote = new Pilote();
                $pilote->setPrenom("Prenom{$i}{$j}")
                       ->setNom("Nom{$i}{$j}")
                       ->setPoints(12)
                       ->setDateStart((new \DateTime())->modify("-" . (2*$j) . " years"))
                       ->setStatut('actif')
                       ->setEcurie($ecurie);
                $manager->persist($pilote);
            }
        }

        $manager->flush();
    }
}
