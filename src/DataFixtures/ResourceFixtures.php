<?php

namespace App\DataFixtures;

use App\Entity\Resource\Resource;
use App\Entity\Resource\ResourceCollection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ResourceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            "VPN" => [
                "NordVPN" => "https://nordvpn.com",
                "ExpressVPN" => "https://expressvpn.com",
                "Surfshark" => "https://surfshark.com",
                "CyberGhost" => "https://cyberghostvpn.com",
                "Private Internet Access" => "https://privateinternetaccess.com",
                "IPVanish" => "https://ipvanish.com",
                "VyprVPN" => "https://vyprvpn.com",
            ],
            "Password Managers" => [
                "1Password" => "https://1password.com",
                "LastPass" => "https://lastpass.com",
                "Bitwarden" => "https://bitwarden.com",
                "Dashlane" => "https://dashlane.com",
                "Keeper" => "https://keepersecurity.com",
                "RoboForm" => "https://roboform.com",
                "Enpass" => "https://enpass.io",
            ],
            "Antivirus" => [
                "Bitdefender" => "https://bitdefender.com",
                "Norton" => "https://norton.com",
                "McAfee" => "https://mcafee.com",
                "Kaspersky" => "https://kaspersky.com",
                "ESET" => "https://eset.com",
                "Avast" => "https://avast.com",
                "AVG" => "https://avg.com",
            ],
            "Cloud Storage" => [
                "Google Drive" => "https://drive.google.com",
                "Dropbox" => "https://dropbox.com",
                "OneDrive" => "https://onedrive.live.com",
                "iCloud" => "https://icloud.com",
                "pCloud" => "https://pcloud.com",
                "Mega" => "https://mega.nz",
                "Sync" => "https://sync.com",
            ],
        ];

        foreach ($categories as $category => $resources) {

            $collection = new ResourceCollection();
            $manager->persist($collection);

            $collection->setName($category);
            $collection->setDescription("The best $category services");
            $collection->setEmoji("🔒");

            foreach ($resources as $title => $url) {
                $resource = new Resource();
                $manager->persist($resource);

                $resource->setCollection($collection);
                $resource->setTitle($title);
                $resource->setUrl($url);
                if(rand(0,1)){
                    $resource->setDescription("The description of $title service");
                }
                if(rand(0,1)){
                    $resource->setRating(rand(1,10)/10);
                }
            }
        }

        $manager->flush();
    }
}
