<?php

use Navigation\Building;

it('returns the correct floor number based on instructions', function (string $fileName, int $expectedFloor) {
    $instructions = file_get_contents(__DIR__ . "/instructions/$fileName.txt");

    $nbOpening = substr_count($instructions, '(');
    $nbClosing = substr_count($instructions, ')');
    $nbElf = substr_count($instructions, '🧝');

    var_dump("Instru: $instructions");
    var_dump("Nb '(': $nbOpening");
    var_dump("Nb ')': $nbClosing");
    var_dump("Nb '🧝': $nbElf");



    $result = Building::whichFloor($instructions);
    expect($result)->toBe($expectedFloor);
})->with([
            ['1', 0],
            ['2', 3],
            ['3', -1],
            ['4', 53],
            ['5', -3],
            ['6', 2920]
        ]);

