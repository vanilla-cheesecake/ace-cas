<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $payment_method = SalesTax::find($id);

    if ($payment_method) {
        $payment_method->delete();
        flashMessage('delete_sales_tax', 'Sales tax deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_sales_tax', 'Invalid sales tax', FLASH_ERROR);
    }
    redirect('../../admin_sales_tax.php');
}


//Add category
if (post('action') === 'add') {

    $sales_tax_name = $_POST['sales_tax_name'];
    $sales_tax_rate = $_POST['sales_tax_rate'];
    $description = $_POST['description'];
    $sales_tax_account_id = $_POST['sales_tax_account_id'];

    try {
        SalesTax::add($sales_tax_name, $sales_tax_rate, $description, $sales_tax_account_id);
        flashMessage('add_sales_tax', 'Sales tax added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_sales_tax', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_sales_tax.php');
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