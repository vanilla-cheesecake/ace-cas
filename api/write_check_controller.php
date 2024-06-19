<?php

require_once __DIR__ . '/../_init.php';
// Include FPDF library
require_once 'fpdf.php';

//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $check = WriteCheck::find($id);

    if ($check) {
        $check->delete();
        flashMessage('delete_check', 'Check deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_check', 'Invalid check', FLASH_ERROR);
    }
    redirect('../admin_write_check.php');
}

if (post('action') === 'update') {
    $name = post('name');
    $id = post('id');

    try {
        $check = WriteCheck::find($id);
        $check->ref_no = $ref_no;
        //$category->update();
        flashMessage('update_category', 'Category updated successfully.', FLASH_SUCCESS);
        redirect('../admin_write_check.php');
    } catch (Exception $ex) {
        flashMessage('update_category', $ex->getMessage(), FLASH_ERROR);
        redirect("../admin_write_check.php?action=update&id={$id}");
    }
}


//Add check
if (post('action') === 'add') {
    try {
        // Collect main form data
        $type = "Check";

        $bank_account_id = $_POST['bank_account_id'];
        $payee_type = $_POST['payee_type'];
        $ref_no = $_POST['ref_no']; // Assuming ref_no is unique per check
        $check_no = $_POST['check_no'];
        $payee_id = $_POST['payee_id'];
        $write_check_date = $_POST['write_check_date'];
        $payee_address = $_POST['payee_address'];
        $memo = $_POST['memo'];
        $gross_amount = $_POST['gross_amount'];
        $discount_percentage = $_POST['discount_percentage'];
        $discount_account_id = $_POST['discount_account_id'];
        $discount_amount = $_POST['discount_amount'];
        $net_amount_due = $_POST['net_amount_due'];
        $vat_percentage = $_POST['vat_percentage'];
        $input_vat_acccount_id = $_POST['vat_account_id'];
        $vat_percentage_amount = $_POST['vat_percentage_amount'];
        $net_of_vat = $_POST['net_of_vat'];
        $tax_withheld_percentage = $_POST['tax_withheld_percentage'];
        $wtax_account_id = $_POST['tax_withheld_account_id'];
        $tax_withheld_amount = $_POST['tax_withheld_amount'];
        $total_amount_due = $_POST['total_amount_due'];

        // Add check to prevent double insertion
        $existingRecord = WriteCheck::findByRefNo($ref_no);
        if ($existingRecord !== null) {
            throw new Exception("Record with Reference No: $ref_no already exists.");
        }

        // Retrieve the last transaction_id
        $transaction_id = WriteCheck::getLastTransactionId();

        // Generate CV number
        $last_transaction_id_str = str_pad($transaction_id, 8, "0", STR_PAD_LEFT); // Pad with zeros to make it 8 digits
        $cv_no = "CV" . $last_transaction_id_str;




        // Add main form data to database (assuming WriteCheck::add() does this)
        WriteCheck::add(
            $cv_no,
            $check_no,
            $bank_account_id,
            $payee_type,
            $ref_no,
            $payee_id,
            $write_check_date,
            $payee_address,
            $memo,
            $gross_amount,
            $discount_amount,
            $net_amount_due,
            $vat_percentage_amount,
            $net_of_vat,
            $tax_withheld_amount,
            $total_amount_due
        );

        // Retrieve the last transaction_id
        $transaction_id = WriteCheck::getLastTransactionId();

        // Decode JSON data
        $items = json_decode($_POST['item_data'], true);
        // Process each item
        // Process each item
        foreach ($items as $item) {
            $account_id_item = $item['account_id'];
            $memo_item = $item['memo'];
            $amount_item = $item['amount'];
            $discount_percentage_item = $item['discount_percentage'];
            $discount_amount_item = $item['discount_amount'];
            $net_amount_before_vat_item = $item['net_amount_before_vat'];
            $net_amount_item = $item['net_amount'];
            $vat_percentage_item = $item['vat_percentage'];
            $input_vat_item = $item['input_vat'];

            // Insert item details into database
            WriteCheck::addItem($transaction_id, $account_id_item, $memo_item, $amount_item, $discount_amount_item, $net_amount_before_vat_item, $net_amount_item, $input_vat_item);
        }

        // Calculate total discount, VAT, and tax withheld amounts
        $total_discount_amount = $discount_amount;
        $total_vat_percentage_amount = $vat_percentage_amount;
        $total_tax_withheld_amount = $tax_withheld_amount;


        // Get unique account IDs from item data
        $unique_account_ids = array_unique(array_column($items, 'account_id'));

        // Calculate amounts for each unique account ID
        $amounts_per_account = [];
        // Calculate discount amounts for each unique account ID
        $discount_amounts_per_account = [];
        foreach ($unique_account_ids as $account_id) {
            $amounts_per_account[$account_id] = 0;
            foreach ($items as $item) {
                if ($item['account_id'] === $account_id) {
                    $amounts_per_account[$account_id] += $item['net_amount'];
                    $discount_amounts_per_account[$account_id] += $item['discount_amount'];
                }
            }
        }


        // Add transaction entries for each unique account ID
        foreach ($unique_account_ids as $account_id) {
            // Add transaction entry for the current account ID with its corresponding amount
            WriteCheck::addTransactionEntries(
                $transaction_id,
                $type,
                $cv_no,
                $account_id,
                $amounts_per_account[$account_id], // Debit
                null, // Crefdit

            );
        }

        // Extract input_vat_account_id from the first row
        $input_vat_account_id_first_row = $items[0]['input_vat_account_id_first_row'];
        // Extract discount_account_id from the first row
        $discount_account_id_first_row = $items[0]['discount_account_id_first_row'];


        // Retrieve the last transaction_id
        $transaction_id = WriteCheck::getLastTransactionId();

        // Add transaction entries for bank account, discount account, VAT account, and tax withheld account
        // Add transaction entries for bank account, discount account, VAT account, and tax withheld account
        WriteCheck::addTransactionEntries($transaction_id, $type, $cv_no, $input_vat_account_id_first_row, $vat_percentage_amount, null);


        // Add transaction entries for bank account, discount account, VAT account, and tax withheld account
        WriteCheck::addTransactionEntries($transaction_id, $type, $cv_no, $discount_account_id_first_row, null, $total_discount_amount);

        // Calculate discount amounts for each unique account ID
        $discount_amounts_per_account = [];
        foreach ($unique_account_ids as $account_id) {
            $discount_amounts_per_account[$account_id] = 0;
            foreach ($items as $item) {
                if ($item['account_id'] === $account_id) {
                    $discount_amounts_per_account[$account_id] += $item['discount_amount'];
                }
            }
        }

        // Add transaction entries for each unique account ID
        foreach ($unique_account_ids as $account_id) {
            // Add transaction entry for the current account ID with its corresponding discount amount
            WriteCheck::addTransactionEntries(
                $transaction_id,
                $type,
                $cv_no,
                $account_id,
                $discount_amounts_per_account[$account_id],
                null // Debit (Discounts usually decrease expenses or costs, so they're usually credited)

            );
        }
        WriteCheck::addTransactionEntries($transaction_id, $type, $cv_no, $wtax_account_id, null, $total_tax_withheld_amount);
        WriteCheck::addTransactionEntries($transaction_id, $type, $cv_no, $bank_account_id, null, $total_amount_due);


        flashMessage('add_write_check', 'Check added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_write_check', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../write_check');
}


//PRINT
if (post('action') === 'print') {

}