<?php

namespace App\DataFixtures;

use App\Entity\Complaint\Complaint;
use App\Entity\Complaint\ComplaintReport;
use App\Repository\Complaint\ComplaintStatusRepository;
use App\Repository\DomainRepository;
use App\Service\DomainService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Intl\Countries;

class ComplaintFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private ComplaintStatusRepository $complaintStatusRepository,
                                private DomainRepository $domainRepository,
                                private DomainService $domainService)
    {
    }

    public function load(ObjectManager $manager): void
    {

        $statuses = $this->complaintStatusRepository->findAll();
        $countries = Countries::getCountryCodes();
        $domains = $this->domainRepository->findAll();


        $letters = range("a", "z");
        foreach ($letters as $letter) {

            $complaint = new Complaint();
            $complaint->setCountry($countries[array_rand($countries)]);

            $complaint->setEmail("$letter@example.com");

            $complaint->setStatus($statuses[array_rand($statuses)]);

            shuffle($domains);
            for ($i = 0; $i < rand(1, 5); $i++) {

                $domain = $domains[$i];

                $report = $this->domainService->getReport("http://$domain");
                $complaintReport = new ComplaintReport($complaint, $report);
                $manager->persist($complaintReport);

            }

            $manager->persist($complaint);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DomainFixtures::class,
            ComplaintStatusFixtures::class,
        ];
    }
}
