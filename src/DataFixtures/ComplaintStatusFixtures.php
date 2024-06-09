<?php

namespace App\DataFixtures;

use App\Entity\Complaint\ComplaintStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ComplaintStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $statuses = [
            0 => "Got contacted",
            1 => "Got contacted, replied",
            2 => "Got contacted, replied, sent sensitive data",
            3 => "Got contacted, replied, sent sensitive data, sent money",
        ];

        foreach ($statuses as $index => $name) {
            $status = new ComplaintStatus();
            $status->setName($name);

            $status->setHasReplied($index > 0);
            $status->setHasSentSensitiveData($index > 1);
            $status->setHasSentMoney($index > 2);

            $manager->persist($status);
        }

        $manager->flush();
    }
}
