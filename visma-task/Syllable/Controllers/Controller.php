<?php

declare(strict_types =1);

namespace Syllable\Controllers;

use Syllable\Database\Database;
use Syllable\Database\DatabaseManager;

class Controller extends Database
{


    public  function  createView()
    {
         Echo "View created";
    }

}