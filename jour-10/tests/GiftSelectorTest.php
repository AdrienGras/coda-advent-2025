<?php
use Gift\GiftSelector;
use Tests\ChildBuilder;

it('selects first feasible gift for nice child under 14', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(12)
            ->nice()
            ->requestingFeasibleGift('Toy')
            ->requestingFeasibleGift('Book')
    );
    expect($result)->toBe('Toy');
});

it('selects last feasible gift for normal child under 14', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(12)
            ->normal()
            ->requestingFeasibleGift('Toy')
            ->requestingFeasibleGift('PS5')
            ->requestingFeasibleGift('Book')
    );
    expect($result)->toBe('Book');
});

it('returns nothing for naughty child under 14 regardless of gifts', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(12)
            ->naughty()
            ->requestingFeasibleGift('Toy')
    );
    expect($result)->toBeNull();
});

it('returns nothing for nice child under 14 with only infeasible gifts', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(12)
            ->nice()
            ->requestingInfeasibleGift()
            ->requestingInfeasibleGift()
    );
    expect($result)->toBeNull();
});

it('returns nothing for normal child under 14 with only infeasible gifts', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(12)
            ->normal()
            ->requestingInfeasibleGift()
            ->requestingInfeasibleGift()
    );
    expect($result)->toBeNull();
});

it('returns nothing for nice child over 14 with low kindness', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.4)
            ->nice()
            ->requestingFeasibleGift('Toy')
    );
    expect($result)->toBeNull();
});

it('selects first feasible gift for nice child over 14 with high kindness', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.6)
            ->nice()
            ->requestingFeasibleGift('Toy')
            ->requestingFeasibleGift('Book')
    );
    expect($result)->toBe('Toy');
});

it('returns nothing for normal child over 14 with low kindness', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.4)
            ->normal()
            ->requestingFeasibleGift('Toy')
    );
    expect($result)->toBeNull();
});

it('selects last feasible gift for normal child over 14 with high kindness', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.6)
            ->normal()
            ->requestingFeasibleGift('Toy')
            ->requestingFeasibleGift('PS5')
            ->requestingFeasibleGift('Book')
    );
    expect($result)->toBe('Book');
});

it('returns nothing for naughty child over 14 regardless of kindness', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.6)
            ->naughty()
            ->requestingFeasibleGift('Toy')
    );
    expect($result)->toBeNull();
});

it('returns nothing for nice child over 14 with high kindness but no feasible gifts', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.6)
            ->nice()
            ->requestingInfeasibleGift()
            ->requestingInfeasibleGift()
    );
    expect($result)->toBeNull();
});

it('returns nothing for normal child over 14 with high kindness but no feasible gifts', function () {
    $result = evaluateRequestFor(
        fn(ChildBuilder $child) => $child
            ->age(15)
            ->kindness(0.6)
            ->normal()
            ->requestingInfeasibleGift()
            ->requestingInfeasibleGift()
    );
    expect($result)->toBeNull();
});

function evaluateRequestFor(callable $childConfiguration): ?string
{
    return GiftSelector::selectGiftFor(
        $childConfiguration(ChildBuilder::aChild())->build()
    );
}
