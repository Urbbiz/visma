<?php

namespace Syllable\Service;

class SyllableResult
{
    public string $dashResult;  // <- zodis su bruksniais.
    public string $withNumbers = '';// <- zodis su skaiciais.
    public string $processingDuration;
    public array $matchedPatterns = []; // <- skiemenys atitinkantys zodzio dalis.
}
