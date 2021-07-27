<?php

declare(strict_types=1);

namespace Syllable\Controllers;

use Syllable\Database\DatabaseManagerInterface;
use Syllable\Database\DatabaseManagerProxy;
use Syllable\Database\DatabaseManagerProxyInterface;
use Syllable\PatternModel;
use Syllable\Database\DatabaseManager;
use Syllable\Service\SyllableAlgorithm;
use Syllable\Service\SyllableAlgorithmInterface;

class WordsController
{

    private DatabaseManagerProxyInterface $dataBaseManagerProxy;
    private SyllableAlgorithmInterface $syllableAlgorithm;
    private DatabaseManagerInterface $dataBaseManager;

    public function __construct(
        DatabaseManagerInterface $dataBaseManager,
        DatabaseManagerProxyInterface $dataBaseManagerProxy,
        SyllableAlgorithmInterface $syllableAlgorithm
    ) {
//
//        $this->dataBaseManagerProxy = new DatabaseManagerProxy();
//        $this->syllableAlgorithm = new SyllableAlgorithm();
//        $this->dataBaseManager = new DatabaseManager();
        //
        $this->dataBaseManager = $dataBaseManager;
        $this->dataBaseManagerProxy = $dataBaseManagerProxy ;
        $this->syllableAlgorithm = $syllableAlgorithm;
    }

    public function getPatterns()
    {
        $results = [];
  //    print_r(self::query("SELECT * FROM Patterns"));

        $patterns = $this->dataBaseManagerProxy->getAllPatterns()->getPatterns();
        foreach ($patterns as $pattern) {
            $results[] = $pattern->__toString();
        }
        header("Content-Type:APPLICATION/JSON");
        echo json_encode($results);
    }

    public function getWords()
    {

//    print_r(self::query("SELECT * FROM Patterns"));
        $words = $this->dataBaseManagerProxy->getAllWords();

        header("Content-Type:APPLICATION/JSON");
        echo json_encode($words);
    }

    public function postWord($givenWord)
    {
        header("Content-Type:APPLICATION/JSON");

        $result = $this ->syllableAlgorithm ->syllableUsingDataBase($givenWord);
        echo json_encode($result);
    }

    public function putWord($givenWord, $editedWord)
    {
        header("Content-Type:APPLICATION/JSON");
        $word = $this->dataBaseManagerProxy->updateWord($givenWord, $editedWord);

        if ($word == false) {
            return false;
        }
        $result = array();
        $result['word'] = $word;
        echo json_encode($result);
    }


    public function deleteWord($givenWord)
    {
        header("Content-Type:APPLICATION/JSON");

        $this ->dataBaseManager->deleteWord($givenWord);
        $result = array();
        $result['message'] = 'Deleted!!!';
        echo json_encode($result);
    }
}
