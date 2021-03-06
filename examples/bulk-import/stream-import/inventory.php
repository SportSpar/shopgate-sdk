<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * preconditions:
 * - location LOCATION_CODE exists
 * - product PRODUCT_CODE and PRODUCT_CODE_SECOND exists
 * - catalog CATALOG_CODE exists
 */
$inventory = provideSampleInventories();

try {
    $handler = $sdk->getBulkImportService()->createStreamImport();
    $inventoryHandler = $handler->createInventoryFeed();
    $inventoryHandler->add($inventory[0]);
    $inventoryHandler->add($inventory[1]);
    $inventoryHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}
