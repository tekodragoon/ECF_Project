<?php

namespace App\DoctrineType;

use App\RecipeType;

class TypeOfRecipe extends AbstractEnumType
{
    public const NAME = 'recipeType';

    public function getName(): string // the name of the type.
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string // the enums class to convert
    {
        return RecipeType::class;
    }
}