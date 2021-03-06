<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * preconditions:
 * - a default catalog exists
 */

try {
    $categories = provideSampleCategories();

    $sdk->getCatalogService()->addCategories($categories);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
