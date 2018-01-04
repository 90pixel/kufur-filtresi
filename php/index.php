<?php

require_once 'Filter.php';

$replacementArray = ["🤐", "😡", "😤", "😠"];

shuffle($replacementArray);

$filter = new \DPX\Filter();
$text = $filter->setHardFile('../hard.txt')
    ->setSoftFile('../soft.txt')
    ->initDictionary()
    ->setText('yarak kürek işler bunlar amk tuzlayarak ansiklopedi')
    ->replace($replacementArray[0]);

echo $text;