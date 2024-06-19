<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $customer = Customer::find($id);

    if ($customer) {
        $customer->delete();
        flashMessage('delete_customer', 'Customer deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_customer', 'Invalid category', FLASH_ERROR);
    }
    redirect('../../admin_customer.php');
}


// Add vendor
if (post('action') === 'add') {
    // Retrieve data from the form
    $customer_name = post('customer_name');
    $customer_code = post('customer_code');
    $customer_address = post('customer_address');
    $contact_number = post('contact_number');
    $customer_email = post('customer_email');
    $customer_tin = post('customer_tin');
    $customer_business_style = post('customer_business_style');

    try {
        // Add the vendor using the Vendor class method
        Customer::add($customer_name, $customer_code, $customer_address, $contact_number, $customer_email, $customer_tin, $customer_business_style);
        flashMessage('add_customer', 'Customer added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_customer', $ex->getMessage(), FLASH_ERROR);
    }

    // Redirect back to the page
    redirect('../../admin_customer.php');
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