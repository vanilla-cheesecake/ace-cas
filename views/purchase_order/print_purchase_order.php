<?php

require_once '_init.php';
// Include FPDF library
require_once 'fpdf186/fpdf.php';


try {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $purchase_order = PurchaseOrder::find($id);
        if ($purchase_order) {
            // Check found, you can now display the details


            // Get the check ID from the request
            $po_id = $_GET['id']; // Assuming the ID is passed as a query parameter

            // Fetch check data based on the provided ID
            $purchase_order = PurchaseOrder::find($po_id);

            // Check if the check exists
            if ($purchase_order) {

                // Create a new PDF instance
                $pdf = new FPDF();
                $pdf->AddPage();

                // Save current position
                $startY = $pdf->GetY();

                // Move to top right corner
                $pdf->SetY(0);
                $pdf->SetX(-1);
                $pdf->SetFont('Arial', '', 8);
                // Write text
                $pdf->Cell(0, 20, 'Original Copy', 0, 0, 'R');

                // Reset Y position
                $pdf->SetY($startY);

                // Add image to the PDF
                $pdf->Image('photos/logo.jpg', 10, 22, 35, 0, 'JPG'); // Adjust path and coordinates as needed
                $pdf->Ln(20); // Adjust vertical position after the image
                // Add check details to the PDF
                $pdf->SetFont('Arial', '', 8);

                $pdf->Cell(0, 2, '1149 C.M. Recto Avenue, Zone 24, Brgy. 266, Sta. Cruz, City of Manila, NCR 1003', 0, 1, 'R');
                $pdf->Cell(0, 4, 'VAT Reg. TIN: 629-138-012-00001', 0, 1, 'R');
                $pdf->Cell(0, 3, 'Tel. No.: +632 82536275', 0, 1, 'R');
                $pdf->Ln(10);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, 'PURCHASE ORDER', 0, 1, 'C');
                $pdf->Ln(15);


                // Add check data to the PDF
                // Set font and size
                $pdf->SetFont('Arial', '', 9);

                // Set maximum width of the cell
                $maxWidth = 80; // Adjust according to your column width requirement

                // Set line height
                $lineHeight = 5; // Adjust according to your line spacing requirement

                // Set padding for the cells (space between left-aligned and right-aligned text)
                $padding = -100; // Adjust as needed

                // Text to be displayed on the left
                $textLeft = 'PO No: ' . $purchase_order->po_no;
                $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 0, 'L');
                // Text to be displayed on the left
                $textLeft = 'Date: ' . $purchase_order->po_date;
                $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 1, 'L');



                // Text to be displayed on the right
                $textRight = 'Vendor: ' . $purchase_order->vendor_name;
                $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textRight, 0, 0, 'L');

                $textRight = 'Delivery Date: ' . $purchase_order->delivery_date;
                $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textRight, 0, 1, 'L');

                // Text to be displayed on the left
                $textLeft = 'Address: ' . $purchase_order->vendor_address;
                $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 0, 'L');
                // Text to be displayed on the left
                $textLeft = 'Terms: ' . $purchase_order->terms;
                $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 1, 'L');



                $pdf->Ln(2);

                // Table headers

                // Center align the table
                $pdf->Ln(); // Move to the next line
                $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line

                $pdf->Cell(40, 10, 'Item Name', 0, 0, 'L');
                $pdf->Cell(40, 10, 'Description', 0, 0, 'L');
                $pdf->Cell(30, 10, 'Qty', 0, 0, 'L');
                $pdf->Cell(25, 10, 'Unit', 0, 0, 'L');
                $pdf->Cell(10, 10, 'Cost', 0, 0, 'R');
                $pdf->Cell(30, 10, 'Amount', 0, 0, 'R');
                $pdf->Ln(); // Move to the next line
                $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line

                // Increment total debit and credit
                $totalCost = 0;
                $totalAmount = 0;


                if ($purchase_order) {
                    foreach ($purchase_order->details as $detail) {

                        // Increment total debit and credit
                        $totalCost += $detail['cost'];
                        $totalAmount += $detail['amount'];


                        $pdf->Cell(40, 10, $detail['item_name'], 0, 0, 'L');
                        $pdf->Cell(40, 10, $detail['purchase_description'], 0, 0, 'L');
                        $pdf->Cell(30, 10, $detail['qty'], 0, 0, 'L');
                        $pdf->Cell(25, 10, $detail['uom_name'], 0, 0, 'L');
                        $pdf->Cell(10, 10, number_format($detail['cost'], 2), 0, 0, 'R');
                        $pdf->Cell(30, 10, number_format($detail['amount'], 2), 0, 0, 'R');

                        $pdf->Ln(5);
                    }
                }


                $pdf->SetFont('Arial', 'B', 9); // 'B' indicates bold
                $pdf->Cell(154, 10, 'Total:', 0, 0, 'R');
                $pdf->Cell(30, 10, 'P' . number_format($totalAmount, 2), 0, 0, 'L');
                $pdf->Ln(15); // Move to the next line after the last row

                $pdf->SetFont('Arial', '', 8);
                $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line
                $pdf->Cell(50, 10, 'PREPARED/POSTED BY:', 0, 0, 'L');
                $pdf->Cell(50, 10, 'CHECKED/CERTIFIED BY:', 0, 0, 'L');
                $pdf->Cell(50, 10, 'VERIFIED FOR PAYMENT BY:', 0, 0, 'L');
                $pdf->Cell(40, 10, 'PAYMENT APPROVED BY:', 0, 0, 'L');
                $pdf->Ln(15); // Move to the next line
                $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line

                $pdf->Ln(2);

                // Set font and size for table content
                // Set font and size for table content
                $pdf->SetFont('Arial', '', 9);
                // Define the width of the cell
                $cellWidth = 150; // Use 0 for auto width
                // Define the height of each line
                $lineHeight = 5; // Adjust as needed
                // Wrap text and add memo to PDF cell
                $pdf->MultiCell($cellWidth, $lineHeight, 'Memo: ' . $purchase_order->memo, 0, 'L', false);

                // Add additional details in the table footer
                $pdf->SetFont('Arial', '', 8);


                $pdf->Ln(10);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(0, 10, '"THIS DOCUMENT IS NOT VALID FOR CLAIM OF INPUT TAX"', 0, 1, 'C');
                $pdf->Ln(20);


                // Add the following code after adding content to your PDF document
                $pdf->SetXY(10, 265); // Adjust the X and Y coordinates as needed
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(0, 3, 'Acknowledgment Certificate Control Number:                        Date Issued:' . date('m/d/Y'), 0, 1, 'L');
                $pdf->Cell(0, 3, 'Series No.: 000000001 to 999999999', 0, 1, 'L');
                $pdf->Cell(0, 3, 'Date and Time Printed: ' . date('m/d/Y h:i:sA'), 0, 1, 'L');



                // Output the PDF
                $pdf->Output();


            } else {
                // Handle the case where the check is not found
                echo "Check not found.";
                exit;
            }
        } else {
            // Handle the case where the ID is not provided
            echo "No ID provided.";
            exit;
        }
    } else {
        // Handle the case where the check ID is invalid or not found
        echo "Check not found!";
    }
} catch (Exception $ex) {
    // Handle any exceptions
    echo "Error: " . $ex->getMessage();
}

