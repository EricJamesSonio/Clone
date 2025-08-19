<?php
require_once(__DIR__ . '/../../db2.php');
require_once(__DIR__ . '/../function.php');

// Helper: get item & ingredient IDs
function id($table, $name) {
    global $con;
    return getIdByName($con, $table, $name);
}

// Drinks
$americanoId = id('starbucksitem', 'Iced Americano');
$latteId     = id('starbucksitem', 'Caffè Latte');
$matchaId    = id('starbucksitem', 'Matcha Green Tea Latte');
$refresherId = id('starbucksitem', 'Very Berry Hibiscus Refresher');
$purpleId    = id('starbucksitem', 'Ice Starbucks Purple Cream');

// Sandwiches
$eggSandId     = id('starbucksitem', 'Egg Sandwich');
$baconSandId   = id('starbucksitem', 'Bacon & Cheese Sandwich');
$cheddarSandId = id('starbucksitem', 'Cheddar Melt Sandwich');
$doubleId      = id('starbucksitem', 'Double-Smoked Bacon, Cheddar & Egg Sandwich');
$turkeyId      = id('starbucksitem', 'Turkey Bacon, Cheddar & Egg White Sandwich');

// Ingredients
$espresso   = id('ingredient', 'Espresso Shot');
$milk       = id('ingredient', 'Milk');
$matcha     = id('ingredient', 'Matcha Powder');
$hibiscus   = id('ingredient', 'Hibiscus Syrup');
$berries    = id('ingredient', 'Blackberries');
$egg        = id('ingredient', 'Egg');
$bacon      = id('ingredient', 'Bacon');
$cheddar    = id('ingredient', 'Cheddar Cheese');
$bread      = id('ingredient', 'Bread');
$icecream   = id('ingredient', 'Ice Cream Mix');
$turkey     = id('ingredient', 'Turkey Bacon');
$eggWhite   = id('ingredient', 'Egg White');

// Recipes
insertData($con, 'item_ingredient',
    ['item_id', 'ingredient_id', 'quantity_value', 'quantity_unit'], [

    // Drinks
    [$americanoId, $espresso, 50, 'ml'],
    [$americanoId, $milk, 0, 'ml'],   // none
    [$latteId, $espresso, 30, 'ml'],
    [$latteId, $milk, 200, 'ml'],
    [$matchaId, $matcha, 5, 'g'],
    [$matchaId, $milk, 180, 'ml'],
    [$refresherId, $hibiscus, 40, 'ml'],
    [$refresherId, $berries, 5, 'pcs'],
    [$purpleId, $icecream, 120, 'ml'],

    // Sandwiches
    [$eggSandId, $egg, 1, 'pcs'],
    [$eggSandId, $bread, 2, 'pcs'],
    [$eggSandId, $cheddar, 20, 'g'],

    [$baconSandId, $bacon, 30, 'g'],
    [$baconSandId, $bread, 2, 'pcs'],
    [$baconSandId, $cheddar, 25, 'g'],

    [$cheddarSandId, $cheddar, 40, 'g'],
    [$cheddarSandId, $bread, 2, 'pcs'],

    [$doubleId, $bacon, 40, 'g'],
    [$doubleId, $cheddar, 25, 'g'],
    [$doubleId, $egg, 1, 'pcs'],
    [$doubleId, $bread, 2, 'pcs'],

    [$turkeyId, $turkey, 35, 'g'],
    [$turkeyId, $cheddar, 20, 'g'],
    [$turkeyId, $eggWhite, 1, 'pcs'],
    [$turkeyId, $bread, 2, 'pcs']
]);
?>
