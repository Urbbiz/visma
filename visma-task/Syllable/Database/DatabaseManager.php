<?php

namespace Syllable\Database;

use PDO;
use Syllable\PatternModel\Pattern;
use Syllable\PatternModel\PatternCollection;

class DatabaseManager extends Database implements DatabaseManagerInterface
{

    //get data from database
    public function getAllPatterns(): PatternCollection
    {
        $sqlQuery = (new DatabaseQueryBuilder())
            ->select()
            ->from(TableNames::PATTERNS);

        $statement = $this->connect()->query($sqlQuery);  // imam data is Pattern table
        $results = new PatternCollection();
        while ($row = $statement->fetch()) {
            $value = $row['value'];
            $results->addPattern(new Pattern($value));
//            echo  $row['value']. "\n";
        }
        return $results;
    }

    public function getAllWords(): array
    {
        $sqlQuery = (new DatabaseQueryBuilder())
            ->select()
            ->from(TableNames::WORDS);

        $statement = $this->connect()->query($sqlQuery);  // imam data is words table
        $results = [];
        while ($row = $statement->fetch()) {
            $result = array();
            $result['id'] = $row['id'];
            $result['value'] = $row['value'];
            $result['syllableValue'] = $row['syllableValue'];
            $result['patterns'] = $this->getRelatedPatterns($row['id']);
            $results[] = $result;
        }
        return $results;
    }

    //  PUT DATA TO DATA BASE
    public function addWord($givenWord, $syllableWord): void
    {


        $statement = $this->connect()->prepare("INSERT INTO Words( `value`,syllableValue)VALUES (?, ?)");  // imam data is Pattern table
        $statement->execute([$givenWord, $syllableWord]);
    }


    public function updateWord($givenWord, $editedWord)
    {
        $word = $this->getWord($givenWord);
        if ($word != false) {
            $statement = $this->connect()->prepare("UPDATE Words SET value = ? WHERE ID=?");  // imam data is Pattern table
            $statement->execute([$editedWord, $word['id']]);
            return $editedWord;
        }
        return false;
    }


    public function getWord($givenWord)
    {
      // patikrinsim db ar jau buvo toks vardas

        $sqlQuery = (new DatabaseQueryBuilder())
            ->select()
            ->from(TableNames::WORDS)
            ->where('`value` = ?');

        $statement = $this->connect()->prepare($sqlQuery);// imam data is Pattern table
//        $statement->bindValue(1, $givenWord);
        $statement->execute([$givenWord]);
        return $statement->fetch();   //jeigu neras grazins false, jei ras, grazins visa rows
    }


    public function setPatternsToDatabase($filePath): void
    {
        $file = new \SplFileObject($filePath);
        while (!$file->eof()) {
            $line = trim($file->fgets());
            list($value) = explode(' ', $line);
            $statement = $this->connect()->prepare("INSERT INTO Patterns( value ) VALUES ( ?)");
            $statement->bindValue(1, $value, PDO::PARAM_STR);
            $statement->execute();
        }
    }

    public function addRelatedPatterns($wordId, $patternIds): void
    {

        foreach ($patternIds as $patternId) {
            $statement = $this->connect()->prepare("INSERT INTO word_patterns VALUES (?,?)");
            $statement->bindValue(1, $wordId, PDO::PARAM_STR);
            $statement->bindValue(2, $patternId, PDO::PARAM_STR);
            $statement->execute();
        }
    }

    public function getRelatedPatterns($wordId): array
    {
        $statement = $this->connect()->prepare("SELECT * FROM Patterns p INNER JOIN  word_patterns wp ON  wp.patternId=p.id WHERE wp.wordId=?");// imam data is Pattern table
        $statement->execute([$wordId]);
        $results = [];
        while ($row = $statement->fetch()) {
            $value = $row['value'];
            $results[] = $value;
        }
        return $results;
    }

    public function getPatternIds($patterns): array
    {


        $statement = $this->connect()->prepare("SELECT * FROM Patterns WHERE `value` IN ('" . implode("','", $patterns) . "')");// imam data is Pattern table
        $statement->execute();
        $results = [];
        while ($row = $statement->fetch()) {
            $value = $row['id'];
            $results[] = $value;
        }
        return $results;
    }


    public function deleteWord($givenWord): void
    {
        $foundWord = $this->getWord($givenWord);
        if ($foundWord != false) {
            $pdoQuery = "DELETE FROM word_patterns WHERE wordId=? ";
            $pdoResult = $this->connect()->prepare($pdoQuery);
            $pdoResult->execute([$foundWord['id']]);

            $pdoQuery = "DELETE FROM Words WHERE id=? ";
            $pdoResult = $this->connect()->prepare($pdoQuery);
            $pdoResult->execute([$foundWord['id']]);
        }
    }
    public function deleteAllTablesData(): void
    {
        $this->deleteConnectionTableData();
        $this->deleteWordsTableData();
        $this->deletePatternsData();
    }

    private function deleteConnectionTableData(): void
    {
        $pdoQuery = "DELETE FROM word_patterns";
//        $pdoQuery = "DELETE FROM Patterns WHERE";
        $pdoResult = $this->connect()->prepare($pdoQuery);
        $pdoExec = $pdoResult->execute();

        if ($pdoExec) {
            echo 'Data from word_patterns table Deleted';
        } else {
            echo 'ERROR Data Not Deleted';
        }
    }

    private function deleteWordsTableData(): void
    {
        $pdoQuery = "DELETE FROM Words";
//        $pdoQuery = "DELETE FROM Patterns WHERE";
        $pdoResult = $this->connect()->prepare($pdoQuery);
        $pdoExec = $pdoResult->execute();

        if ($pdoExec) {
            echo 'Data from Words table Deleted';
        } else {
            echo 'ERROR Data Not Deleted';
        }
    }

    private function deletePatternsData(): void
    {
        $pdoQuery = "DELETE FROM Patterns";
//        $pdoQuery = "DELETE FROM Patterns WHERE";
        $pdoResult = $this->connect()->prepare($pdoQuery);
        $pdoExec = $pdoResult->execute();

        if ($pdoExec) {
            echo 'Data from Patterns table Deleted';
        } else {
            echo 'ERROR Data Not Deleted';
        }
    }
}
