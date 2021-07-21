<?php
namespace Syllable\Database;

namespace Syllable\Database;


use Syllable\PatternModel\Pattern;
use Syllable\PatternModel\PatternCollection;



class DatabaseManagerProxy
{

    private ?PatternCollection $patternCollection = NULL;   // Cache rezultata

    private array $Words= [];   //tables in database;
    private array $Patterns= [];
    private array $WordPatterns = [];
    private DatabaseManager $dataBaseManager;

    public function __construct()
    {

        $this->dataBaseManager = new DatabaseManager();
    }

    public function getAllPatterns(): PatternCollection
    {
        if ($this->patternCollection === null) {
            $this->patternCollection = $this->dataBaseManager->getAllPatterns() ;
        }
        return $this->patternCollection;
    }

    public function getAllWords(): array
    {
        if (count($this->Words) === 0) {

           $this->Words = $this->dataBaseManager->getAllWords();
        }

        return $this->Words;
    }
}
