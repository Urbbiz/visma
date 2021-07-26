<?php


use PHPUnit\Framework\TestCase;
use Syllable\PatternModel\Pattern;
use Syllable\PatternModel\PatternCollection;
use Syllable\PatternModel\PatternExtractor;

class PatternTest extends TestCase
{
    function testGetPatternsWithoutNumber()
    {
        $pattern = new Pattern("an3dri2us");
        $this->assertEquals("andrius", $pattern->getPatternWithoutNumbers());
    }

    function testCreatePattern()
    {
        $pattern = new Pattern("returnPattern");
        $this->assertInstanceOf(Pattern::class, $pattern);
    }

}