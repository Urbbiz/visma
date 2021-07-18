<?php
namespace Syllable\PatternModel;
use  Syllable\PatternModel;

interface PatternExtractorInterface
{
    public function  getPatterns($filePath) : PatternCollection;  // grazina PatternCollection klases objekta


}