<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\User;
use App\RecipeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        $user = new User();
        $user->setEmail('user@example.com');
        $userPassword = $this->hasher->hashPassword($user, 'user1234');
        $user->setPassword($userPassword);
        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $adminPassword = $this->hasher->hashPassword($admin, 'admin1234');
        $admin->setPassword($adminPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Create exercice recipes (Creating base menu)

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
            $recipe->setType(RecipeType::entree);
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
            $recipe->setName($mainName[$i]);
            $recipe->setDescription($mainDesc[$i]);
            $recipe->setPrice($mainPrice[$i]);
            $recipe->setType(RecipeType::main);
            $manager->persist($recipe);
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
        $dessertPrice = [8 , 10, 11, 9, 12, 9, 12, 12];

        for ($i = 0; $i < 8; $i++) {
            $recipe = new Recipe();
            $recipe->setName($dessertName[$i]);
            $recipe->setDescription($dessertDesc[$i]);
            $recipe->setPrice($dessertPrice[$i]);
            $recipe->setType(RecipeType::dessert);
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
