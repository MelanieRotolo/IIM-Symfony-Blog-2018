<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Créer trois catégories fakées

        for ($i = 1 ; $i <= 3 ; $i++){
            $caterory = new Category();
            $caterory -> setTitle($faker -> sentence())
                      -> setDescription($faker -> paragraph());

            $manager -> persist($caterory);

            //Créer entre quatre et six articles

            for ($j = 1; $j <= mt_rand(4, 6) ; $j++){
                $article = new Article();

                $content = '<p>' . join($faker -> paragraphs(5), '</p><p>') . '</p>';


                $article -> setTitle($faker -> sentence())
                    -> setContent($content)
                    -> setImage($faker -> imageUrl())
                    -> setCreatedAt($faker -> dateTimeBetween('-6 months'))
                    -> setCategory($caterory);

                $manager -> persist($article);

                // Commentaire pour les articles
                for ($k = 1 ; $k <= mt_rand(4, 10) ; $k++)
                {
                    $comment = new Comment();

                    $content = '<p>' . join($faker -> paragraphs(2), '</p><p>') . '</p>';

                    $now = new \DateTime();

                    $interval = $now -> diff($article -> getCreatedAt());

                    $days = $interval -> days;

                    $minimum = '-' . $days . 'days' ; // -100 days

                    $comment -> setAuthor($faker -> name)
                             -> setContent($content)
                             -> setCreatedAt($faker -> dateTimeBetween($minimum))
                             -> setArticle($article);

                    $manager -> persist($comment);
                }

            }
        }

        $manager->flush();
    }
}
