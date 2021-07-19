<?php

declare(strict_types =1);

namespace Syllable\Controllers;

use Syllable\PatternModel;
use Syllable\Database\DatabaseManager;
use Syllable\Service\SyllableAlgorithm;

class WordsController extends Controller
{



  public function  arMatai()
  {
      echo "Ar matai uzrasa???";
  }

  public function getPatterns()
{
    $results =[];
//    print_r(self::query("SELECT * FROM Patterns"));
      $dataBaseManager = new DatabaseManager();
      $patterns= $dataBaseManager->getAllPatterns()->getPatterns();
      foreach ($patterns as $pattern){
          $results[]= $pattern->__toString();
      }
      header("Content-Type:APPLICATION/JSON");
      echo json_encode($results);
}

    public function getWords()
    {

//    print_r(self::query("SELECT * FROM Patterns"));
        $dataBaseManager = new DatabaseManager();
        $words= $dataBaseManager->getAllWords();

        header("Content-Type:APPLICATION/JSON");
        echo json_encode($words);
    }

    public function  postWord($givenWord)
    {
        header("Content-Type:APPLICATION/JSON");
        $syllableAlgorithm = new SyllableAlgorithm();
        $result = $syllableAlgorithm ->syllableUsingDataBase($givenWord);
        echo json_encode($result);

    }

    public function  putWord($givenWord, $editedWord)
    {
        header("Content-Type:APPLICATION/JSON");
        $dataBaseManager = new DatabaseManager();
        $word = $dataBaseManager->updateWord($givenWord, $editedWord);


        if($word == false) {
            return false;
        }

        $result= array();
        $result['word'] = $word;
        echo json_encode($result);

    }


    public function  deleteWord($givenWord)
    {
        header("Content-Type:APPLICATION/JSON");
        $dataBaseManager = new DatabaseManager();
        $dataBaseManager->deleteWord($givenWord);
        $result= array();
        $result['message'] = 'Deleted!!!';
        echo json_encode($result);

    }

//    private function __construct()
//    {
//        if (!file_exists(DIR . 'data/accounts.json')) {    // pirma karta sukuriam json faila, jeigu jo dar nera
//            $data = json_encode([]); //uzkoduojam sita faila i json kaip masyva, ojame yra objektai
//            file_put_contents(DIR . 'data/accounts.json', $data); //irasom i json faila.
//        }
//        $data = file_get_contents(DIR . 'data/accounts.json'); // jeigu jau egzistuoja, pasiimu faila
//        $this->data = json_decode($data); //iraso objekta this data i json faila.
//    }
}