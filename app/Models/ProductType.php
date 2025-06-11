<?php

namespace App\Models;

enum ProductType: string
{
    case fruit = 'fruit';
    case veggie = 'veggie';
    case bread = 'bread';
    case snack = 'snack';
}