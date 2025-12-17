<?php
declare(strict_types=1);

use Routing\Gift;
use Routing\GiftRouter;

beforeEach(function () {
    $this->router = new GiftRouter();
});

test('Null gift -> ERROR', function () {
    expect($this->router->route(null))->toBe('ERROR');
});

dataset('blank zones', [
    [null],
    [''],
    [' '],
    ['   '],
    ["\t"],
]);

test('Empty/blank zone -> WORKSHOP-HOLD')
    ->with('blank zones')
    ->expect(fn ($zone) => $this->router->route(new Gift(1.0, false, $zone)))
    ->toBe('WORKSHOP-HOLD');

dataset('routing matrix', [
    // weight, fragile, zone, expected
    [2.0,  true,  'EU',   'REINDEER-EXPRESS'],
    [2.1,  true,  'EU',   'SLED'],
    [10.1, false, 'EU',   'SLED'],
    [9.9,  false, 'EU',   'REINDEER-EXPRESS'],
    [9.9,  false, 'NA',   'REINDEER-EXPRESS'],
    [9.9,  false, 'APAC', 'SLED'],
]);

test('Routing matrix (no null/blank zone)', function (float $w, bool $fragile, string $zone, string $expected) {
    $gift = new Gift($w, $fragile, $zone);
    expect($this->router->route($gift))->toBe($expected);
})->with('routing matrix');