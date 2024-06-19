<?php

require_once __DIR__.'/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $vendor = Vendor::find($id);

    if ($vendor){
        $vendor->delete();
        flashMessage('delete_vendor', 'Vendor deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_vendor', 'Invalid category', FLASH_ERROR);
    }
    redirect('../../admin_vendor.php');
}


// Add vendor
if (post('action') === 'add') {
    // Retrieve data from the form
    $vendor_name = post('vendorname');
    $vendor_code = post('vendorcode');
    $account_number = post('accountnumber');
    $vendor_address = post('vendoraddress');
    $contact_number = post('contactnumber');
    $email = post('email');
    $terms = post('terms');

    try {
        // Add the vendor using the Vendor class method
        Vendor::add($vendor_name, $vendor_code, $account_number, $vendor_address, $contact_number, $email, $terms);
        flashMessage('add_vendor', 'Vendor added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_vendor', $ex->getMessage(), FLASH_ERROR);
    }

    // Redirect back to the page
    redirect('../../admin_vendor.php');
}


//Update category

// if (post('action') === 'update') {
//     $name = post('name');
//     $id = post('id');

//     try {
//         $category = Vendor::find($id);
//         $category->name = $name;
//         $category->update();
//         flashMessage('update_category', 'Category updated successfully.', FLASH_SUCCESS);
//         redirect('../admin_category.php');
//     } catch (Exception $ex) {
//         flashMessage('update_category', $ex->getMessage(), FLASH_ERROR);
//         redirect("../admin_category.php?action=update&id={$id}");
//     }
// }