
[Sample code for connecting item and ingredients]

createTable($con, 'item_ingredient', "
    CREATE TABLE IF NOT EXISTS item_ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        ingredient_id INT NOT NULL,
        quantity_value DECIMAL(10, 2) NOT NULL,          -- e.g., 50
        quantity_unit VARCHAR(20) NOT NULL,              -- e.g., 'g', 'ml', 'pcs'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (ingredient_id) REFERENCES ingredient(id) ON DELETE CASCADE,
        UNIQUE (item_id, ingredient_id)
    )

createTable($con, 'ingredient', "
    CREATE TABLE IF NOT EXISTS ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        quantity_in_stock DECIMAL(10, 2) DEFAULT 0,        -- Stock level in bulk unit (e.g., 5.00 kg)
        stock_unit VARCHAR(20),                            -- e.g., 'kg', 'L', 'pcs'
        supplier_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE
    )

createTable($con, 'starbucksitem', "
    CREATE TABLE starbucksitem (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,        category_id INT NOT NULL,
        subcategory_id INT NOT NULL,
        description TEXT,
        image_url VARCHAR(255), 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES category(id),
        FOREIGN KEY (subcategory_id) REFERENCES subcategory(id)


[Future implementations]
-- 


[item ingredients]

<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'item_ingredient', "
    CREATE TABLE IF NOT EXISTS item_ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        ingredient_id INT NOT NULL,
        quantity_value DECIMAL(10, 2) NOT NULL,          -- e.g., 50
        quantity_unit VARCHAR(20) NOT NULL,              -- e.g., 'g', 'ml', 'pcs'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (ingredient_id) REFERENCES ingredient(id) ON DELETE CASCADE,
        UNIQUE (item_id, ingredient_id)
    )
");

?>

[ingredients]

<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'ingredient', "
    CREATE TABLE IF NOT EXISTS ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        quantity_in_stock DECIMAL(10, 2) DEFAULT 0,        -- Stock level in bulk unit (e.g., 5.00 kg)
        stock_unit VARCHAR(20),                            -- e.g., 'kg', 'L', 'pcs'
        supplier_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE
    )
");

?>

[starbucksitem]

<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'ingredient', "
    CREATE TABLE IF NOT EXISTS ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        quantity_in_stock DECIMAL(10, 2) DEFAULT 0,        -- Stock level in bulk unit (e.g., 5.00 kg)
        stock_unit VARCHAR(20),                            -- e.g., 'kg', 'L', 'pcs'
        supplier_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE
    )
");

?>

<?php

require_once(__DIR__ . '/../db2.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'size', "
    CREATE TABLE size (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        name            VARCHAR(50)    NOT NULL UNIQUE,       -- e.g. Tall, Grande, Venti
        price_modifier  DECIMAL(10,2)  NOT NULL DEFAULT 0.00,  -- surcharge over base price
        created_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
        updated_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP 
                          ON UPDATE CURRENT_TIMESTAMP
    )
");

createTable($con, 'item_size', "
    CREATE TABLE item_size (
        item_id  INT NOT NULL,
        size_id  INT NOT NULL,
        PRIMARY KEY (item_id, size_id),
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (size_id) REFERENCES size(id)        ON DELETE CASCADE
    )
");