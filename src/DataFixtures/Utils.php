<?php

namespace App\DataFixtures;
use Faker;
use Transliterator;

class Utils
{
    private Faker\Generator $faker;
    private Transliterator $trans;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
        $this->trans = Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC');
    }

    public function setIdentity($object): void
    {
        $lastname = $this->faker->unique()->lastName();
        $firstname = $this->faker->unique()->firstName();
        $l = $this->trans->transliterate(strtolower($lastname));
        $f = $this->trans->transliterate(strtolower($firstname));
        $email = $l . '-' . $f . '@example.com';
        $object->setEmail($email);
        $object->setLastname($lastname);
        $object->setFirstname($firstname);
    }
}