<?php
namespace Syllable\Database;




use Syllable\Database\DatabaseManagerProxyInterface;
use Syllable\PatternModel\PatternCollection;



class DatabaseManagerProxy implements DatabaseManagerProxyInterface
{

    private ?PatternCollection $patternCollection = NULL;   // Cache rezultata

    private array $Words= [];   //tables in database;
    private array $Patterns= [];
    private array $WordPatterns = [];
    private DatabaseManager $dataBaseManager;

    public function __construct(DatabaseManagerInterface $dataBaseManager)
    {

        $this->dataBaseManager = $dataBaseManager;
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
