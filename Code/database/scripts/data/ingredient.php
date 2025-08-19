<?php
require_once(__DIR__ . '/../../db2.php');
require_once(__DIR__ . '/../function.php');

// Insert ingredients
insertData($con, 'ingredient',
    ['name', 'quantity_in_stock', 'stock_unit'], [
    
    ['Espresso Shot', 500, 'ml'],       // assume bulk in ml
    ['Milk', 20000, 'ml'],
    ['Matcha Powder', 500, 'g'],
    ['Hibiscus Syrup', 2000, 'ml'],
    ['Blackberries', 300, 'pcs'],
    ['Egg', 100, 'pcs'],
    ['Bacon', 2000, 'g'],
    ['Cheddar Cheese', 1500, 'g'],
    ['Bread', 500, 'pcs'],
    ['Ice Cream Mix', 5000, 'ml'],
    ['Turkey Bacon', 1000, 'g'],
    ['Egg White', 200, 'pcs']
]);
?>
