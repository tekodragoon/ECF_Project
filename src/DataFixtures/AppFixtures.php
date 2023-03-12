<?php

namespace App\DataFixtures;

use App\Entity\Formula;
use App\Entity\GalleryImage;
use App\Entity\Menu;
use App\Entity\OpeningDays;
use App\Entity\OpeningHours;
use App\Entity\Recipe;
use App\Entity\RecipeCategory;
use App\Entity\Restaurant;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Transliterator;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create user and admin

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $adminPassword = $this->hasher->hashPassword($admin, 'admin1234');
        $admin->setPassword($adminPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');
        $trans = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $lastname = $faker->unique()->lastName();
            $firstname = $faker->unique()->firstName();
            $l = $trans->transliterate(strtolower($lastname));
            $f = $trans->transliterate(strtolower($firstname));
            $email = $l . '-' . $f . '@example.com';
            $user->setEmail($email);
            $userPassword = $this->hasher->hashPassword($user, $f . '1234');
            $user->setPassword($userPassword);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $manager->persist($user);
        }

        // Create Restaurant

        $restaurant = new Restaurant();

        for ($i = 0; $i < 7; $i++) {
            $openHour = new OpeningHours();
            $openHour->setDayOfWeek($i);
            $openDay = new OpeningDays();
            $openDay->setDayOfWeek($i);
            if ($i <= 4) {
                $openDay->setOpen(true);
                $openDay->setNoonService(true);
                $openHour->setNoonStart(DateTime::createFromFormat('H:i', "11:30"));
                $openHour->setNoonEnd(DateTime::createFromFormat('H:i', "15:00"));
            }
            if ($i <= 5) {
                $openDay->setOpen(true);
                $openDay->setEveningService(true);
                $openHour->setEveningStart(DateTime::createFromFormat('H:i', "19:00"));
                $openHour->setEveningEnd(DateTime::createFromFormat('H:i', "23:00"));
            }
            $restaurant->addOpenHour($openHour);
            $restaurant->addOpenDay($openDay);
        }
        $manager->persist($restaurant);

        // Create exercice recipes (Creating base menu)

        $cat1 = new RecipeCategory();
        $cat1->setName('Entrées');
        $cat1->setListOrder(1);
        $manager->persist($cat1);

        $cat2 = new RecipeCategory();
        $cat2->setName('Plats');
        $cat2->setListOrder(2);
        $manager->persist($cat2);

        $cat3 = new RecipeCategory();
        $cat3->setName('Dessert');
        $cat3->setListOrder(3);
        $manager->persist($cat3);

        $entreeName = [
            'Betteraves rôties',
            'Maquereau à la flamme',
            'Pamplemousse et fenouil',
            'Carpaccio de courgettes et poulpe grillé',
            'Fricassée d\'escargots de Bourgogne',
            'Galet de saumon, fleur d\'ail et haricot coco',
            'Espadon grillé sauce miso rouge',
            'Pressé de foie gras, fruits rouges et estragon',
        ];
        $entreeDesc = [
            'Avec une vinaigrette au citron, ail et basilic.',
            'Maquereau à la flamme, légumes provençaux et caviar d\'aubergine.',
            'Une salade fraîche de pamplemousse acidulé, avec de l\'huile d\'olive.',
            'Du poulpe grillé à point sur un lit de courgettes fines et fondantes.',
            'Fricassée d\'escargots de Bourgogne à l\'aubergine et émulsion de saté.',
            'Galet de saumon, fleur d\'ail et haricot coco, crémeux au chorizo, carotte pourpre senteur des sous bois.',
            'Espadon et petits légumes grillés à la flamme, accompagné de leur sauce au miso rouge.',
            'Pressé de foie gras accompagné par ses fruits rouges frais et sa crème d\'estragon.',
        ];
        $entreePrice = [8, 10, 9, 12, 14, 12, 14, 15];

        for ($i = 0; $i < 8; $i++) {
            $recipe = new Recipe();
            $recipe->setName($entreeName[$i]);
            $recipe->setDescription($entreeDesc[$i]);
            $recipe->setPrice($entreePrice[$i]);
            $recipe->setCategory($cat1);
            $manager->persist($recipe);
        }

        $mainName = [
            'Gratin de millet, champignons et poireaux',
            'Navarin de dinde',
            'Entrecôte de porc et crème campagnarde',
            'Papillote de merlu au citron confit',
            'Saumon aux épices trappeur et écrasé de pommes de terre',
            'Courge à la tomme et champignons',
            'Waterzoï de poisson',
            'Aiguillette de poulet et crème de brocoli',
        ];
        $mainDesc = [
            'Onctueux gratin de millet, poireau et champignons relevé par un délicat curry et gratiné au comté.',
            'Mijoté de petits légumes primeurs et fondant morceaux de dinde.',
            'Une entrecôte de porc aveyronnais accompagné de sa crème moutarde à l\'ancienne et ses oignons caramélisés. Servi avec des légumes rôtis au four.',
            'Papillote de merlu cuit vapeur avec sa sauce au citron confit, adouci par une fondue de poireau.',
            'Un filet de saumon cuit au four relevé par ses épices douces. Accompagné de son écrasé de pommes de terre.',
            'De délicieux petits  potimarrons farcis d\'une duxelle de champignons et de tomme de savoie.',
            'Du poisson cuit au court-bouillon délicatement crémé et garni de légumes savoureux.',
            'Des aiguillettes de poulet déglacées dans du vinaigre balsamique, accompagné d\'une crémeuse purée de brocolis.'
        ];
        $mainPrice = [14, 17, 21, 19, 21, 18, 20, 18];

        for ($i = 0; $i < 8; $i++) {
            $recipe = new Recipe();
            $galleryImage = new GalleryImage();

            $recipe->setName($mainName[$i]);
            $recipe->setDescription($mainDesc[$i]);
            $recipe->setPrice($mainPrice[$i]);
            $recipe->setCategory($cat2);

            $galleryImage->setName($mainName[$i]);
            $galleryImage->setFilename($mainName[$i] . '.jpeg');
            $galleryImage->setRecipe($recipe);

            $manager->persist($recipe);
            $manager->persist($galleryImage);
        }

        $dessertName = [
            'Pudding aux dattes',
            'Fondant praliné',
            'Chiboust miel safran',
            'Pavlova fraise verveine',
            'Crème brulée butternut, mousse pain d\'épices',
            'Marmelade de pomme et raisins à la cardamome',
            'L\'olivier',
            'Ravioles cubes marron chocolat épices',
        ];
        $dessertDesc = [
            'Avec une sauce caramel au beurre et da la crème glacée à la vanille.',
            'Un délicieux fondant au chocolat avec son coeur praliné.',
            'Tuile croquante surmontée de crème pâtissière miel safran, confiture de pamplemousse et sorbet au miel.',
            'Une délicieuse meringue accompagné de sa crème verveine.',
            'Une surprenante crème brulée au butternut, mousse de pain d\'épices et gelée à l\'orange.',
            'Marmelade de pomme et de raisin à la cardamome, sablé et glace à la noix.',
            'Entremet au chocolat, croustillant au gianduja et coulis de mûres.',
            'Ravioles de gelée d\'épices, fourrée d\'une ganache au chocolat et d\'une mousse aux marrons.',
        ];
        $dessertPrice = [8, 10, 11, 9, 12, 9, 12, 12];

        for ($i = 0; $i < 8; $i++) {
            $recipe = new Recipe();
            $recipe->setName($dessertName[$i]);
            $recipe->setDescription($dessertDesc[$i]);
            $recipe->setPrice($dessertPrice[$i]);
            $recipe->setCategory($cat3);
            $manager->persist($recipe);
        }

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
