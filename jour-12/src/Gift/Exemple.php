<?php
require_once "Machine.php";

use Gift\Builder\BookBuilder;
use Gift\Builder\BuilderRegistry;
use Gift\Builder\CarBuilder;
use Gift\Builder\DollBuilder;
use Gift\Builder\TeddyBuilder;
use Gift\Delivery;
use Gift\ErrorHandler;
use Gift\GiftMachine;
use Gift\Logger;
use Gift\Ribbon;
use Gift\Wrapper;

echo "🎅 Bienvenue à l'atelier du Pôle Nord !\n";
echo "Démarrage de la machine...\n\n";

// Initialisation des dépendances
$logger = new Logger();
$errorHandler = new ErrorHandler($logger);
$wrapper = new Wrapper();
$ribbon = new Ribbon();
$delivery = new Delivery();

$builder = new BuilderRegistry();
$builder->registerBuilder('teddy', new TeddyBuilder());
$builder->registerBuilder('car', new CarBuilder());
$builder->registerBuilder('doll', new DollBuilder());
$builder->registerBuilder('book', new BookBuilder());

// Création de la machine à cadeaux
$machine = new GiftMachine(
    $builder,
    $wrapper,
    $ribbon,
    $delivery,
    $logger,
    $errorHandler
);

$machine->createGift('teddy', 'Alice');
$machine->createGift("car", 'Bob');
$machine->createGift('robot', 'Charlie');
$machine->createGift('doll', 'Diane');
$machine->createGift('book', 'Eliott');