<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $discount = Discount::find($id);

    if ($discount) {
        $discount->delete();
        flashMessage('delete_discount', 'Discount successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_discount', $ex->getMessage(), FLASH_ERROR);
    }
    redirect('../../admin_discount.php');
}


//Add category
if (post('action') === 'add') {

    $discount_name = $_POST['discount_name'];
    $discount_rate = $_POST['discount_rate'];
    $description = $_POST['description'];
    $discount_account_id = $_POST['discount_account_id'];

    try {
        Discount::add($discount_name, $discount_rate, $description, $discount_account_id);
        flashMessage('add_discount', 'Discount added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_discount', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_discount.php');
}


//Update category

// if (post('action') === 'update') {
//     $name = post('name');
//     $id = post('id');

//     try {
//         $category = Uom::find($id);
//         $category->name = $name;
//         $category->update();
//         flashMessage('update_uom', 'Uom updated successfully.', FLASH_SUCCESS);
//         redirect('../admin_category.php');
//     } catch (Exception $ex) {
//         flashMessage('update_uom', $ex->getMessage(), FLASH_ERROR);
//         redirect("../admin_category.php?action=update&id={$id}");
//     }
// }