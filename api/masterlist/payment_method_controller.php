<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $payment_method = PaymentMethod::find($id);

    if ($payment_method) {
        $payment_method->delete();
        flashMessage('delete_payment_method', 'Payment method deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_payment_method', 'Invalid payment method', FLASH_ERROR);
    }
    redirect('../../admin_payment_method.php');
}


//Add category
if (post('action') === 'add') {

    $payment_method_name = $_POST['payment_method_name'];
    $description = $_POST['description'];

    try {
        PaymentMethod::add($payment_method_name, $description);
        flashMessage('add_payment_method', 'Payment method added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_payment_method', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_payment_method.php');
}


//Update category

if (post('action') === 'update') {
    $name = post('name');
    $id = post('id');

    try {
        $category = Uom::find($id);
        $category->name = $name;
        $category->update();
        flashMessage('update_uom', 'Uom updated successfully.', FLASH_SUCCESS);
        redirect('../admin_category.php');
    } catch (Exception $ex) {
        flashMessage('update_uom', $ex->getMessage(), FLASH_ERROR);
        redirect("../admin_category.php?action=update&id={$id}");
    }
}