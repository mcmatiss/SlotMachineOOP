<?php
require_once "Symbol.php";

// Note chanceToOccur values must add up to 100
$symbols = [
    new Symbol("7", 4, 25),
    new Symbol("$", 4, 25),
    new Symbol("★", 4, 25),
    new Symbol("❤", 10, 10),
    new Symbol("♦", 10, 10),
    new Symbol("ϟ", 20, 5),
];
$occurrence = 0;
foreach ($symbols as $symbol) {
    $occurrence = $occurrence + $symbol->getChanceToOccur();
    $symbol->setChanceToOccur($occurrence);
}
if ($occurrence !== 100) {
    exit("\nError chanceToOccur values must add up to 100!\n");
}

$baseBet = 5;
$rows = 3;
$columns = 3;

// [row, column]
$winConditions = [
    [[1, 1], [1, 2], [1, 3]],
    [[2, 1], [2, 2], [2, 3]],
    [[3, 1], [3, 2], [3, 3]],
    [[1, 1], [2, 1], [3, 1]],
    [[1, 2], [2, 2], [3, 2]],
    [[1, 3], [2, 3], [3, 3]],
];

do {
    $playerCoins = (int) readline(
        "Enter the amount of coins to play with (min $baseBet): "
    );
    if ($playerCoins < $baseBet) {
        echo "\nInvalid amount.\n";
    }
} while ($playerCoins < $baseBet);

do {
    echo "\nYour balance: $playerCoins\n";
    $playerBet = (int) readline(
        "Enter BET amount per single game round (min $baseBet): "
    );
    if ($playerBet > $playerCoins) {
        echo "\nInvalid amount.\n";
    }
} while ($playerBet > $playerCoins);

do {
    // Generates new game board
    $gameBoard = [];
    $generatedSymbol = "";
    for ($row = 1; $row <= $rows; $row++) {
        for ($column = 1; $column <= $columns; $column++) {
            $randomSymbol = rand(1, 100);
            foreach ($symbols as $symbol) {
                if ($randomSymbol <= $symbol->getChanceToOccur()) {
                    $generatedSymbol = $symbol->getVisual();
                    break;
                }
            }
            $gameBoard[$row][$column] = $generatedSymbol;
        }
    }

    // Displays game board
    echo PHP_EOL;
    foreach ($gameBoard as $elements) {
        foreach ($elements as $element) {
            echo $element;
        }
        echo PHP_EOL;
    }
    echo PHP_EOL;

    // Checks if win conditions are met and (if they are) adds a multiplier
    $multipliers = 0;
    $winingSymbols = [];
    foreach ($winConditions as $winCondition) {
        $combination = 0;
        $firstElementOfCondition =
            $gameBoard[$winCondition[0][0]][$winCondition[0][1]];
        foreach ($winCondition as $coordinates) {
            [$row, $column] = $coordinates;
            if ($gameBoard[$row][$column] === $firstElementOfCondition) {
                $combination++;
            } else {
                break;
            }
        }
        if ($combination === count($winCondition)) {
            foreach ($symbols as $multiplier) {
                if ($multiplier->getVisual() === $firstElementOfCondition) {
                    $multipliers =
                        $multipliers +
                        $multiplier->getMultiplier() * count($winCondition);
                }
            }
            $winingSymbols[] = $firstElementOfCondition;
        }
    }

    if ($multipliers > 0) {
        echo "Congratulations! You win with: ";
        foreach ($winingSymbols as $winingSymbol) {
            echo "[$winingSymbol]";
        }
        $payout = $playerBet + $multipliers * ($playerBet / $baseBet);
        echo "\nPayout: $payout ";
    } else {
        $payout = 0;
    }
    $playerCoins = $playerCoins + $payout - $playerBet;
    echo "Your balance: $playerCoins\n";
    do {
        $playGame = readline("Do you wish to continue playing? [y/n]: ");
        if (strtolower($playGame) === "n") {
            exit("Thanks for playing!\n");
        }
    } while (strtolower($playGame) !== "y");
    if ($playerCoins < $playerBet) {
        echo "\nInsufficient funds!\n";
    }
} while ($playerCoins >= $playerBet);
