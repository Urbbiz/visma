<?php


namespace Syllable\Database;


use Syllable\PatternModel\Pattern;
use Syllable\PatternModel\PatternCollection;

interface DatabaseManagerInterface
{

    public function getAllPatterns(): PatternCollection;


    public function getAllWords(): array;


    //  PUT DATA TO DATA BASE
    public function addWord($givenWord, $syllableWord): void;



    public function updateWord($givenWord, $editedWord);


    public function getWord($givenWord);


    public function setPatternsToDatabase($filePath):void;


    public function addRelatedPatterns($wordId, $patternIds):void;


    public function getRelatedPatterns($wordId): array;


    public function getPatternIds($patterns): array;


    public function deleteAllTablesData():void;


    public function deleteWord($givenWord):void;



}