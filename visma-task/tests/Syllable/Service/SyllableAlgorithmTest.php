<?php

namespace Syllable\Service;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Syllable\Database\DatabaseManagerInterface;
use Syllable\IO\UserInputReaderInterface;
use Syllable\PatternModel\Pattern;
use Syllable\PatternModel\PatternCollection;
use Syllable\PatternModel\PatternExtractorInterface;

const DIR = __DIR__ . '/';   //constants case sensitive


class SyllableAlgorithmTest extends TestCase
{
//    public function testSyllable(){
//        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
//        $userInputReader =  $this->createMock(UserInputReaderInterface::class);
//        $patternExtractor = $this->createMock(PatternExtractorInterface::class);
//        $logger = $this->createMock(LoggerInterface::class);
////        $userInputReader->getInputWord(ret)->willreturn('Andrius');
////        ir patern extractor
//
//        $patternCollection = new PatternCollection();
//        $patternCollection->addPattern(new Pattern('.An3'));
//        $patternCollection->addPattern(new Pattern('dri2us'));
//        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);
//        $result = $syllableAlgorithm->syllable("Andrius", $patternCollection);
//
//        $this->assertSame("An-drius", $result->dashResult);
//        $this->assertSame("An3dri2us", $result->withNumbers);
//        $this->assertCount(2, $result->matchedPatterns);
//    }

    public function TestSyllableWordProvider()
    {
        return [
            ["Mandrius", "Mandrius", "Mandri2us",1],
            ["Andrius", "An-drius", "An3dri2us",2],
            ["Andrejus", "An-drejus", "An3drejus",1],
            ["Marius", "Marius", "Marius",0]
        ];
    }

    /**
     * @dataProvider TestSyllableWordProvider
     */
    public function testsyllableWord($givenWord, $expectedDashResult, $expectedWithNumbers, $expectedCount)
    {
        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
        $userInputReader =  $this->createMock(UserInputReaderInterface::class);
        $patternExtractor = $this->createMock(PatternExtractorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $userInputReader->expects($this->once())->method("getInputWord")->willReturn($givenWord);

        $patternCollection = new PatternCollection();
        $patternCollection->addPattern(new Pattern('.An3'));
        $patternCollection->addPattern(new Pattern('dri2us'));

        $patternExtractor->expects($this->once())
            ->method("getPatterns")
            ->with($this->stringContains("inputfile.txt"))
            ->willReturn($patternCollection);


        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);
        $result = $syllableAlgorithm->syllableWord();

        $this->assertSame($expectedDashResult, $result->dashResult);
        $this->assertSame($expectedWithNumbers, $result->withNumbers);
//        var_dump($result->matchedPatterns);
        $this->assertCount($expectedCount, $result->matchedPatterns);
    }

    public function testsyllableWord_conecutive()
    {
        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
        $userInputReader =  $this->createMock(UserInputReaderInterface::class);
        $patternExtractor = $this->createMock(PatternExtractorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $userInputReader->method("getInputWord")->willReturnOnConsecutiveCalls("Andrius", "Andrejus");

        $patternCollection = new PatternCollection();
        $patternCollection->addPattern(new Pattern('.An3'));
        $patternCollection->addPattern(new Pattern('dri2us'));

        $patternExtractor->expects($this->exactly(2))
            ->method("getPatterns")
            ->with($this->stringContains("inputfile.txt"))
            ->willReturn($patternCollection);

        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);

        $result = $syllableAlgorithm->syllableWord();
        $this->assertSame("An-drius", $result->dashResult);
        $this->assertSame("An3dri2us", $result->withNumbers);
        $this->assertCount(2, $result->matchedPatterns);

        $result = $syllableAlgorithm->syllableWord();
        $this->assertSame("An-drejus", $result->dashResult);
        $this->assertSame("An3drejus", $result->withNumbers);
        $this->assertCount(1, $result->matchedPatterns);
    }

    public function TestSyllableUsingDatabaseProvider()
    {
        return [
            ["Andrius", "An-drius", "An3dri2us",2]
        ];
    }
    /**
     * @dataProvider TestSyllableUsingDatabaseProvider
     */
    public function testSyllableUsingDatabase($givenWord, $expectedDashResult, $expectedWithNumbers, $expectedCount)
    {
        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
        $userInputReader =  $this->createMock(UserInputReaderInterface::class);
        $patternExtractor = $this->createMock(PatternExtractorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        $patternCollection = new PatternCollection();
        $patternCollection->addPattern(new Pattern('.An3'));
        $patternCollection->addPattern(new Pattern('dri2us'));

        $databaseManager->expects($this->atLeast(1))
            ->method("getAllPatterns")
            ->willReturn($patternCollection);

        $relatedPatterns = [];
        $relatedPatterns[] = '.An3';
        $relatedPatterns [] = 'dri2us';

        $databaseManager->expects($this->atLeast(1))
            ->method("getRelatedPatterns")
            ->willReturn($relatedPatterns);

        $word = [] ;
        $word["id"] = 1;
        $word["value"] = "Andrius" ;
        $word["syllableValue"] = "An-drius" ;

        $databaseManager->expects($this->once())
            ->method("getWord")
            ->willReturn($word);

        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);
        $result = $syllableAlgorithm->syllableUsingDataBase($givenWord);

        $this->assertSame($expectedDashResult, $result->dashResult);
        $this->assertCount($expectedCount, $result->matchedPatterns);
    }


    public function TestSyllableSentenceProvider()
    {
        return [
            ["Mistranslate Andrius!!!", "Mis-trans-late An-drius!!! ", "M2is1t4ra2n2s3l2ate "]
        ];
    }

    /**
     * @dataProvider TestSyllableSentenceProvider()
     */
    public function testsyllableSentence($givenSentence, $expectedDashResult)
    {
        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
        $userInputReader =  $this->createMock(UserInputReaderInterface::class);
        $patternExtractor = $this->createMock(PatternExtractorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $userInputReader->expects($this->once())->method("getInputSentence")->willReturn($givenSentence);

        $sentenceWordArray = [];
        $sentenceWordArray[] = "Mistranslate";
        $sentenceWordArray[] = "Andrius!!!";

        $userInputReader->expects($this->once())->method("getSentenceWordsInArray")->willReturn($sentenceWordArray);

        $patternCollection = new PatternCollection();
        $patternCollection->addPattern(new Pattern('.mis1'));
        $patternCollection->addPattern(new Pattern('a2n'));
        $patternCollection->addPattern(new Pattern('4and'));
        $patternCollection->addPattern(new Pattern('1dr'));
        $patternCollection->addPattern(new Pattern('i1u'));
        $patternCollection->addPattern(new Pattern('m2is'));
        $patternCollection->addPattern(new Pattern('2n1s2'));
        $patternCollection->addPattern(new Pattern('n2sl'));
        $patternCollection->addPattern(new Pattern('s1l2'));
        $patternCollection->addPattern(new Pattern('s3lat'));
        $patternCollection->addPattern(new Pattern('st4r'));
        $patternCollection->addPattern(new Pattern('1tra'));
        $patternCollection->addPattern(new Pattern('2us'));

        $patternExtractor->expects($this->once())
            ->method("getPatterns")
            ->with($this->stringContains("inputfile.txt"))
            ->willReturn($patternCollection);

        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);
        $result = $syllableAlgorithm->syllableSentence();

        $this->assertSame($expectedDashResult, $result);
    }
}
