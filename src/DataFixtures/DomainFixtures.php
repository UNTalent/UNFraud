<?php

namespace App\DataFixtures;

use App\Entity\Analysis;
use App\Entity\Domain;
use App\Entity\Rating;
use App\Entity\Report;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DomainFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $levels = [
            "Official" => [
                "un.org",
                "unicef.org",
            ],
            "Unofficial" => [
                "untalent.org"
            ],
            "Unreliable" => [
                "gmail.com",
                "hotmail.com"
            ],
            "Scammer" => [
                "headoffice-un.org",
                "wfp.pacific.emploiportal.work",
                "jobcareer-department.cc",
                "jobcareer-departments.com",
                "jobcareerportal.cc",
                "uncied.org",
                "csonetundesa.org",
                "jobcareer-portal.cc",
                "unsdg-emp.org",
                "csonet-undesa.com",
                "csonethk-desa.org",
            ],
        ];

        $i = 0;
        foreach ($levels as $title => $domains) {

            $level = 1-($i/(count($levels)-1));

            $rating = new Rating();
            $rating->setTitle("Rated $title");
            $rating->setDescription("Explaining what is rated $title");
            $rating->setToDo("What you should do if it is rated $title");
            $rating->setLevel($level);
            $rating->setIsDangerous($level < 0.3 ? true : ($level > 0.6 ? false : null));
            $rating->setCssClass(strtolower($title));

            $manager->persist($rating);

            $analysis = new Analysis();
            $analysis->setTitle("$title website");
            $analysis->setInstructions("What to do with $title websites.");
            $analysis->setRating($rating);

            $manager->persist($analysis);

            foreach ($domains as $hostname) {
                $domain = new Domain($hostname);
                $manager->persist($domain);

                for($j=0; $j<rand(0, 20); $j++) {
                    $report = new Report("example@$hostname", $domain);
                    $manager->persist($report);
                }

                $analysis->addDomain($domain);
            }

            $i++;
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
