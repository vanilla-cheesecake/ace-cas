<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $other_name = OtherNameList::find($id);

    if ($other_name) {
        $other_name->delete();
        flashMessage('delete_other_name', 'Other Name deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_other_name', 'Invalid category', FLASH_ERROR);
    }
    redirect('../../admin_other_name.php');
}


// Add other name
if (post('action') === 'add') {
    // Retrieve data from the form
    $other_name = post('other_name');
    $other_name_code = post('other_name_code');
    $account_number = post('account_number');
    $other_name_address = post('other_name_address');
    $contact_number = post('contact_number');
    $email = post('email');
    $terms = post('terms');

    try {
        // Add the other name using the OtherNameList class method
        OtherNameList::add($other_name, $other_name_code, $account_number, $other_name_address, $contact_number, $email, $terms);
        flashMessage('add_other_name', 'Other name added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_other_name', $ex->getMessage(), FLASH_ERROR);
    }

    // Redirect back to the page
    redirect('../../other_name');
}


//Update category

// if (post('action') === 'update') {
//     $name = post('name');
//     $id = post('id');

//     try {
//         $category = OtherNameList::find($id);
//         $category->name = $name;
//         $category->update();
//         flashMessage('update_category', 'Category updated successfully.', FLASH_SUCCESS);
//         redirect('../admin_category.php');
//     } catch (Exception $ex) {
//         flashMessage('update_category', $ex->getMessage(), FLASH_ERROR);
//         redirect("../admin_category.php?action=update&id={$id}");
//     }
// }