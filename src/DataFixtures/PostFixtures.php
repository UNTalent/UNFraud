<?php

namespace App\DataFixtures;

use App\Entity\Post\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i < 5; $i++){
            $post = new Post();
            $post->setTitle("This is the title of the article $i");
            $post->setIntro("This is the introduction of the article $i");
            $post->setContent("This is the content. It should **support markdown**\n\n1. And lists\n1. With numbers");

            $manager->persist($post);

        }
        // $product = new Product();

        $manager->flush();
    }
}
