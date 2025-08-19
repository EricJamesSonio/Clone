

<?php
require_once(__DIR__ . '/../db2.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'ingredient', "
    CREATE TABLE IF NOT EXISTS ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        quantity_in_stock DECIMAL(10, 2) DEFAULT 0, -- bulk stock in base unit
        stock_unit VARCHAR(20),                     -- 'kg', 'L', 'pcs'
        supplier_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
");


?>
