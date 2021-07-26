<?php


use PHPUnit\Framework\TestCase;
use Syllable\PatternModel\PatternExtractor;

class PatternTest extends TestCase
{

    function testGetPatternsWithoutNumber()
{
    $patternExtractor = new PatternExtractor("returnPattern");
    $this->assertInstanceOf(PatternExtractor::class, $patternExtractor);

}
}