

<?php

require_once(__DIR__ . '/../db2.php');
require_once(__DIR__ . '/../scripts/function.php');



createTable($con, 'item_ingredient', "
    CREATE TABLE IF NOT EXISTS item_ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        ingredient_id INT NOT NULL,
        quantity_value DECIMAL(10, 2) NOT NULL,  -- amount per item
        quantity_unit VARCHAR(20) NOT NULL,      -- e.g., 'g', 'ml', 'pcs'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (ingredient_id) REFERENCES ingredient(id) ON DELETE CASCADE,
        UNIQUE (item_id, ingredient_id)
    )
");




?>