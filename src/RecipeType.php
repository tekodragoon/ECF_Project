<?php

namespace App;

enum RecipeType: string
{
    case entree = "Entrée";
    case main = "Plat";
    case dessert = "Dessert";
}