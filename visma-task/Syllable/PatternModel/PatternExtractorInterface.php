<?php
namespace Syllable\PatternModel;


interface PatternExtractorInterface
{
    public function  getPatterns($filePath) : PatternCollection;  // grazina PatternCollection klases objekta


}