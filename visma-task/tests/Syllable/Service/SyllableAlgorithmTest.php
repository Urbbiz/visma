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


}