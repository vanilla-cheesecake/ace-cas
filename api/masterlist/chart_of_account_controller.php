<?php

require_once __DIR__.'/../../_init.php';


//Delete category
// Delete chart of account
if (get('action') === 'delete') {
    $id = get('id');
    $account = ChartOfAccount::find($id);

    if ($account) {
        $account->delete();
        flashMessage('delete_coa', 'Chart of account deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_coa', 'Invalid chart of account', FLASH_ERROR);
    }
    redirect('../../admin_chart_of_accounts.php');
}

// Add account to chart of accounts
if (post('action') === 'add') {
    $accountType = post('accounttype');
    $accountCode = post('accountCode');
    $accountName = post('accountname');
  
    $description = post('description');
    $subAccount = post('subaccountof'); // You may need to adjust this based on how sub accounts are handled

    try {
        ChartOfAccount::add($accountType, $accountName, $accountCode, $description, $subAccount);
        flashMessage('add_coa', 'Account added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_coa', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_chart_of_accounts.php'); // You may need to adjust the redirect URL
}



//Update category

if (post('action') === 'update') {
    $name = post('name');
    $id = post('id');

    try {
        $category = Category::find($id);
        $category->name = $name;
        $category->update();
        flashMessage('update_category', 'Category updated successfully.', FLASH_SUCCESS);
        redirect('../admin_category.php');
    } catch (Exception $ex) {
        flashMessage('update_category', $ex->getMessage(), FLASH_ERROR);
        redirect("../admin_category.php?action=update&id={$id}");
    }
}