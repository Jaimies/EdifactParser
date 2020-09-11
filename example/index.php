<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use EdifactParser\EdifactParser;
use EdifactParser\IO\ConsolePrinter;

$fileContent = file_get_contents(__DIR__ . '/edifact-sample.edi');
$messages = EdifactParser::create()->parse($fileContent);

$printer = new ConsolePrinter();

foreach ($messages as $i => $message) {
    print "Message number: {$i}\n";
    $printer->printMessage($message);
    print PHP_EOL;
}
