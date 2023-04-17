<?php

namespace App\DataFixtures;

use App\Entity\Formula;
use App\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $menu1 = new Menu();
        $menu1->setTitle('Menu du marché');
        $menu1->setDescription('Du lundi au vendredi uniquement.');
        $menu1form1 = new Formula();
        $menu1form1->setName('Entrée et plat du jour');
        $menu1form2 = new Formula();
        $menu1form2->setName('Plat et dessert du jour');
        $menu1->addFormula($menu1form1);
        $menu1->addFormula($menu1form2);
        $menu1->setPrice(20);
        $menu1->setActive(true);
        $manager->persist($menu1);

        $menu2 = new Menu();
        $menu2->setTitle('Menu du midi');
        $menu2->setDescription('Uniquement le midi sur une selection de plats.');
        $menu2form1 = new Formula();
        $menu2form1->setName('Entrée et plat');
        $menu2form2 = new Formula();
        $menu2form2->setName('Plat et dessert');
        $menu2->addFormula($menu2form1);
        $menu2->addFormula($menu2form2);
        $menu2->setPrice(18);
        $menu2->setActive(true);
        $manager->persist($menu2);

        $menu3 = new Menu();
        $menu3->setTitle('Menu découverte');
        $menu3->setDescription('Tous les soirs.');
        $menu3form1 = new Formula();
        $menu3form1->setName('Entrée');
        $menu3form2 = new Formula();
        $menu3form2->setName('Plat');
        $menu3form3 = new Formula();
        $menu3form3->setName('Dessert');
        $menu3->addFormula($menu3form1);
        $menu3->addFormula($menu3form2);
        $menu3->addFormula($menu3form3);
        $menu3->setPrice(35);
        $menu3->setActive(true);
        $manager->persist($menu3);

        $manager->flush();
    }
}