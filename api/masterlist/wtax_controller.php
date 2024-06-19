<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $wtax = WithholdingTax::find($id);

    if ($wtax) {
        $wtax->delete();
        flashMessage('delete_withholding_tax', 'Withholding tax deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_withholding_tax', 'Withholding sales tax', FLASH_ERROR);
    }
    redirect('../../admin_withholding_tax.php');
}


//Add category
if (post('action') === 'add') {

    $wtax_name = $_POST['wtax_name'];
    $wtax_rate = $_POST['wtax_rate'];
    $description = $_POST['description'];
    $wtax_account_id = $_POST['wtax_account_id'];

    try {
        WithholdingTax::add($wtax_name, $wtax_rate, $description, $wtax_account_id);
        flashMessage('add_witholding_tax', 'Withholding Tax added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_witholding_tax', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_withholding_tax.php');
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