

<?php
require_once(__DIR__ . '/../db2.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'ready_item_stock', "
    CREATE TABLE IF NOT EXISTS ready_item_stock (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        size_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 0, -- how many ready-to-sell items
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (size_id) REFERENCES size(id) ON DELETE CASCADE,
        UNIQUE (item_id, size_id)
    )
");
