<?php
require_once __DIR__ . '/../../_init.php';

// if (get('action') === 'add') {
//     $name = post('name');
//     $category_id = post('category_id');
//     $quantity = post('quantity');
//     $price = post('price');

//     try {
//         Product::add($name, $category_id, $quantity, $price);
//         flashMessage('add_product', 'Product added successfully.', FLASH_SUCCESS);
//     } catch (Exception $ex) {
//         flashMessage('add_product', 'An error occured', FLASH_ERROR);
//     }
//     redirect('../../admin_add_item.php');
// }

if (get('action') === 'add') {
    $vendor_id = post('preferred_vendor_id');
    $category_id = post('category_id');
    $uom_id = post('uom_id');
    $itemcogsaccount_id = post('itemcogsaccount_id');
    $itemincomeaccount_id = post('itemincomeaccount_id');
    $itemassetsaccount_id = post('itemassetsaccount_id');
    $item_name = post('item_name');
    $item_code = post('item_code');
    $item_type = post('item_type');
    $reorder_point = post('reorder_point');
    $sales_description = post('sales_description');
    $selling_price = post('selling_price');
    $purchase_description = post('purchase_description');
    $cost_price = post('cost_price');
    $quantity = post('quantity');

    try {
        Product::add(
            $vendor_id,
            $category_id,
            $uom_id,
            $itemcogsaccount_id,
            $itemincomeaccount_id,
            $itemassetsaccount_id,
            $item_name,
            $item_code,
            $item_type,
            $preferred_vendor,
            $reorder_point,
            $sales_description,
            $selling_price,
            $purchase_description,
            $cost_price,
            $quantity
        );
        flashMessage('add_product', 'Product added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_product', 'An error occurred: ' . $ex->getMessage(), FLASH_ERROR);
    }
    redirect('../../admin_add_item.php');
}

if (get('action') === 'delete') {
    $id = get('id');

    Product::find($id)?->delete();

    flashMessage('delete_product', 'Product deleted successfully.', FLASH_SUCCESS);
    redirect('../../admin_add_item.php');
}

if (get('action') === 'update') {
    $product = Guard::hasModel(Product::class);
    $product->name = post('name');
    $product->category_id = post('category_id');
    $product->price = post('price');
    $product->update();

    flashMessage('update_product', 'Product updated successfully', FLASH_SUCCESS);
    redirect('../admin_update_item.php?id=' . $product->id);
}

if (get('action') === 'add_stock') {
    $product = Guard::hasModel(Product::class);
    $product->quantity += get('quantity');
    $product->update();

    flashMessage('add_stock', "Stocks quantity updated successfully", FLASH_SUCCESS);
    redirect('../admin_home.php');
}