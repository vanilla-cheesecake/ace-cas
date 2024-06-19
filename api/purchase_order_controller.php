<?php

require_once __DIR__ . '/../_init.php';
// Include FPDF library
require_once 'fpdf.php';

if (post('action') === 'add') {
    try {
        // Process your form data here
        // You can access form fields like $_POST['po_no'], $_POST['po_date'], etc.
        // For example:
        $po_no = $_POST['po_no'];
        $po_date = $_POST['po_date'];
        $delivery_date = $_POST['delivery_date'];
        $vendor_id = $_POST['vendor_id'];
        $vendor_address = $_POST['vendor_address'];
        $terms = $_POST['terms'];
        $gross_amount = $_POST['gross_amount'];
        $discount_amount = $_POST['discount_amount'];
        $net_amount_due = $_POST['net_amount_due'];
        $input_vat_amount = $_POST['input_vat_amount'];
        $vatable_amount = $_POST['vatable_amount'];
        $zero_rated_amount = $_POST['zero_rated_amount'];
        $vat_exempt_amount = $_POST['vat_exempt_amount'];
        $total_amount_due = $_POST['total_amount_due'];
        $memo = $_POST['memo'];

        // Add check to prevent double insertion
        $existingRecord = PurchaseOrder::findByPoNo($po_no);
        if ($existingRecord !== null) {
            throw new Exception("Record with PO #: $po_no already exists.");
        }


        PurchaseOrder::add(
            $po_no,
            $po_date,
            $delivery_date,
            $vendor_id,
            $terms,
            $gross_amount,
            $discount_amount,
            $net_amount_due,
            $input_vat_amount,
            $vatable_amount,
            $zero_rated_amount,
            $vat_exempt_amount,
            $total_amount_due,
            $memo
        );


        // Retrieve the last transaction_id
        $transaction_id = PurchaseOrder::getLastTransactionId();

        // Decode JSON data
        $items = json_decode($_POST['item_data'], true);

        // Process the items
        foreach ($items as $item) {
            // Extract item details
            $item_id = $item['item_id'];
            $description = $item['description'];
            $uom = $item['uom'];
            $quantity = $item['quantity'];
            $cost = $item['cost'];
            $amount = $item['amount'];
            $discount_percentage = $item['input_vat_id'];
            $discount_amount = $item['discount_amount'];
            $net_amount_before_input_vat = $item['net_amount_before_input_vat'];
            $net_amount = $item['net_amount'];
            $input_vat_percentage = $item['input_vat_id'];
            $input_vat_amount = $item['input_vat_amount'];

            // Insert each item into the database or perform desired processing
            // $stmt = $connection->prepare("INSERT INTO purchase_order_items (...) VALUES (...)");
            // $stmt->execute([...]);
            PurchaseOrder::addItem(
                $transaction_id,
                $item_id,
                $quantity,
                $cost,
                $amount,
                $discount_percentage,
                $discount_amount,
                $net_amount_before_input_vat,
                $net_amount,
                $input_vat_percentage,
                $input_vat_amount
            );
        }

        // You can output a success message or redirect the user to another page after successful processing.
        flashMessage('add_purchase_order', 'Purchase order added!.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        // Handle any exceptions that occur during processing
        // You can log the error, display a user-friendly error message, or take other appropriate actions.
        flashMessage('add_purchase_order', $ex->getMessage(), FLASH_ERROR);
    }
    redirect('../purchase_order');



    if (get('action') === 'update') {
        $poId = get('id');
        $purchaseOrder = PurchaseOrder::find($poId);
        if (!$purchaseOrder) {
            // Handle the case where the purchase order is not found
            echo "Purchase order not found.";
            exit;
        }
        // Now you have $purchaseOrder available to use in your view
    }

}

