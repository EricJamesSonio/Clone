<?php

require_once(__DIR__ . '/../../db2.php');
require_once(__DIR__ . '/../function.php');

// Fetch all Foreign keys!
$drinkId     = getIdByName($con, 'category', 'Drink');
$sandwichId  = getIdByName($con, 'category', 'Sandwich');

$espressoId  = getIdByName($con, 'subcategory', 'Espresso');
$teaId       = getIdByName($con, 'subcategory', 'Tea');
$fruityId    = getIdByName($con, 'subcategory', 'Fruity');
$eggId       = getIdByName($con, 'subcategory', 'Egg');
$baconId     = getIdByName($con, 'subcategory', 'Bacon');
$cheddarId   = getIdByName($con, 'subcategory', 'Cheddar');

// --- Insert items (without quantity, since stock is tracked in ready_item_stock)
insertData($con, 'starbucksitem', 
    ['name', 'price', 'category_id', 'subcategory_id', 'description', 'image_url'], [

    ['Iced Americano', 140.00, $drinkId, $espressoId, 'A refreshing espresso drink served over ice', 'americano.jpg'],
    ['Caffè Latte', 155.00, $drinkId, $espressoId, 'Rich espresso with steamed milk and light foam', 'latte.jpg'],
    ['Matcha Green Tea Latte', 165.50, $drinkId, $teaId, 'Smooth and creamy matcha green tea with milk', 'matcha.jpg'],
    ['Very Berry Hibiscus Refresher', 170.00, $drinkId, $fruityId, 'Fruity drink with real blackberries and green coffee extract', 'refresher.jpg'],
    ['Egg Sandwich', 130.00, $sandwichId, $eggId, 'Classic breakfast sandwich with egg and cheese', 'egg.jpg'],
    ['Bacon & Cheese Sandwich', 145.00, $sandwichId, $baconId, 'Savory sandwich with crispy bacon and melted cheese', 'bacon.jpg'],
    ['Cheddar Melt Sandwich', 135.00, $sandwichId, $cheddarId, 'Warm sandwich with rich cheddar cheese filling', 'cheddar.jpg'],
    ['Ice Starbucks Purple Cream', 120.00, $drinkId, $fruityId, 'Ice cream Supreme Taste!', 'cream.jpg'],
    ['Double-Smoked Bacon, Cheddar & Egg Sandwich', 395.00, $sandwichId, $baconId, 'A hearty sandwich with double-smoked bacon, cheddar, and egg', 'BaconGoudaEggSandwich.jpg'],
    ['Turkey Bacon, Cheddar & Egg White Sandwich', 382.00, $sandwichId, $baconId, 'A lighter option with turkey bacon, cheddar, and egg white', 'DoubleSmokedBaconEggSandwich.jpg']
]);

?>
