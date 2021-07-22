<?php


namespace Syllable\Database;


use Syllable\PatternModel\PatternCollection;

interface DatabaseManagerProxyInterface
{

    public function getAllPatterns(): PatternCollection;

    public function getAllWords(): array;


}