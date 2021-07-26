<?php
namespace Syllable\Service;

use Psr\Log\LoggerInterface;
use Syllable\Database\DatabaseManagerInterface;
use Syllable\IO\UserInputReaderInterface;
use Syllable\PatternModel\Pattern;
use Syllable\PatternModel\PatternCollection;
use Syllable\PatternModel\PatternExtractorInterface;

const DIR = __DIR__ . '/';   //constants case sensitive


class SyllableAlgorithmTest extends \PHPUnit\Framework\TestCase
{
    public function testSyllable(){
        $databaseManager =  $this->createMock(DatabaseManagerInterface::class);
        $userInputReader =  $this->createMock(UserInputReaderInterface::class);
        $patternExtractor = $this->createMock(PatternExtractorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
//        $userInputReader->getInputWord(ret)->willreturn('Andrius');
//        ir patern extractor

        $patternCollection = new PatternCollection();
        $patternCollection->addPattern(new Pattern('.An3'));
        $patternCollection->addPattern(new Pattern('dri2us'));
        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor, $logger);
        $result = $syllableAlgorithm->syllable("Andrius", $patternCollection);

        $this->assertSame("An-drius", $result->dashResult);
        $this->assertSame("An3dri2us", $result->withNumbers);
        $this->assertCount(2, $result->matchedPatterns);
    }

    public function TestSyllableWordProvider()
    {
        return array(
            array("Mandrius", "Mandrius", "Mandri2us",1),
            array("Andrius", "An-drius", "An3dri2us",2),
            array("Andrejus", "An-drejus", "An3drejus",1),
            array("Marius", "Marius", "Marius",0)
        );
    }

    /**
     * @dataProvider TestSyllableWordProvider
     */
    public function testsyllableWord($givenWord, $expectedDashResult, $expectedWithNumbers, $expectedCount){
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


        $syllableAlgorithm = new SyllableAlgorithm($databaseManager, $userInputReader, $patternExtractor,$logger);
        $result = $syllableAlgorithm->syllableWord();

        $this->assertSame($expectedDashResult, $result->dashResult);
        $this->assertSame($expectedWithNumbers, $result->withNumbers);
//        var_dump($result->matchedPatterns);
        $this->assertCount($expectedCount, $result->matchedPatterns);
    }

}