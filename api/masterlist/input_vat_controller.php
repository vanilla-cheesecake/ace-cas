<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $payment_method = InputVat::find($id);

    if ($payment_method) {
        $payment_method->delete();
        flashMessage('delete_input_vat', 'Sales tax deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_input_vat', 'Invalid sales tax', FLASH_ERROR);
    } 
    redirect('../../admin_input_vat.php');
}


//Add category
if (post('action') === 'add') {

    $input_vat_name = $_POST['input_vat_name'];
    $input_vat_rate = $_POST['input_vat_rate'];
    $description = $_POST['description'];
    $input_vat_account_id = $_POST['input_vat_account_id'];

    try {
        InputVat::add($input_vat_name, $input_vat_rate, $description, $input_vat_account_id);
        flashMessage('add_input_vat', 'Input Vat added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_input_vat', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../input_vat');
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