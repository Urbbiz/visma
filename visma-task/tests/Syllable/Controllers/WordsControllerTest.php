<?php


use Syllable\Controllers\WordsController;
use Syllable\Database\DatabaseManagerInterface;

class WordsControllerTest extends \PHPUnit\Framework\TestCase
{

    public function testPOST()
    {
        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
        $databaseManagerProxy = $this->createMock(\Syllable\Database\DatabaseManagerProxyInterface::class);
        $syllableAlgorithm = $this->createMock(\Syllable\Service\SyllableAlgorithmInterface::class);
        $wordsController = new WordsController($databaseManager,$databaseManagerProxy,$syllableAlgorithm);


    }

}