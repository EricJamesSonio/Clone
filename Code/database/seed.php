<?php
require_once(__DIR__ . '/db2.php');
require_once(__DIR__ . '/scripts/function.php');

// --- MODEL CREATION (tables) ---
require_once(__DIR__ . '/model/category.php');
require_once(__DIR__ . '/model/subcategory.php');
require_once(__DIR__ . '/model/attributes_templates.php');
require_once(__DIR__ . '/model/contacts.php');
require_once(__DIR__ . '/model/users.php');
require_once(__DIR__ . '/model/admins.php');
require_once(__DIR__ . '/model/auth.php');
require_once(__DIR__ . '/model/user_order.php');
// Ensure product table exists before any FKs referencing it
require_once(__DIR__ . '/model/starbucksitem.php'); 

// Size and item_size (now safe, starbucksitem exists)
require_once(__DIR__ . '/model/size.php');          // also creates item_size

// Tables referencing starbucksitem and/or size
require_once(__DIR__ . '/model/itemattributes.php');
require_once(__DIR__ . '/model/orderitems.php');
require_once(__DIR__ . '/model/receipts.php');
require_once(__DIR__ . '/model/cart_items.php');
require_once(__DIR__ . '/model/discount.php');
require_once(__DIR__ . '/model/country.php');
require_once(__DIR__ . '/model/province.php');
require_once(__DIR__ . '/model/city.php');
require_once(__DIR__ . '/model/address.php');
require_once(__DIR__ . '/model/inventory_setting.php');
require_once(__DIR__ . '/model/ingredient.php');       // ✅ add ingredients
require_once(__DIR__ . '/model/item_ingredient.php');  // ✅ add item-ingredients
require_once(__DIR__ . '/model/ready_item_stock.php'); // keep last after item/ingredients

// --- DATA SEEDING ---

// 1) core lookups & static data
require_once(__DIR__ . '/scripts/data/category_data.php');
require_once(__DIR__ . '/scripts/data/subcategory_data.php');
require_once(__DIR__ . '/scripts/data/attributes_templates_data.php');
require_once(__DIR__ . '/scripts/data/users.php');
require_once(__DIR__ . '/scripts/data/admins.php');
require_once(__DIR__ . '/scripts/data/contacts.php');
require_once(__DIR__ . '/scripts/data/auth.php');
require_once(__DIR__ . '/scripts/data/address.php');
require_once(__DIR__ . '/scripts/data/discounts.php');
require_once(__DIR__ . '/scripts/data/inventory_setting.php');

// 2) seed items (just the products, no sizes yet)
require_once(__DIR__ . '/scripts/data/starbucksitem.php');
require_once(__DIR__ . '/scripts/data/item_attributes_data.php');

// 3) seed sizes lookup  
require_once(__DIR__ . '/scripts/data/size.php');

// 4) map each drink → all sizes  
require_once(__DIR__ . '/scripts/data/item_size.php');

// 5) seed ingredients
require_once(__DIR__ . '/scripts/data/ingredient.php');   // ✅

// 6) seed item_ingredient mappings
require_once(__DIR__ . '/scripts/data/item_ingredient.php'); // ✅

// 7) seed ready-to-sell stock (depends on above)
require_once(__DIR__ . '/scripts/data/ready_item_stock.php');

// 8) seed some orders so order_item has something to reference  
require_once(__DIR__ . '/scripts/data/sample_order.php');

// 9) now seed order_items (drinks will get valid size_ids)  
require_once(__DIR__ . '/scripts/data/order_item.php');

// (optionally) 10) seed receipts, etc.
// require_once(__DIR__ . '/scripts/data/receipts_data.php');

echo "✅ All tables created and seeded successfully.";
