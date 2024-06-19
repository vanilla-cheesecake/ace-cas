<?php

require_once '_init.php';
// Include FPDF library
require_once 'fpdf186/fpdf.php';


try {

    if (isset($_GET['id']) || isset($_GET['ref_no'])) {
        $id = $_GET['id'];

        $check = WriteCheck::find($id);
        if ($check) {
            // Check found, you can now display the details
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

    // Get the check ID from the request
    $checkId = $_GET['id']; // Assuming the ID is passed as a query parameter

    // Fetch check data based on the provided ID
    $check = WriteCheck::find($checkId);

    // Check if the check exists
    if ($check) {

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
        $pdf->Cell(0, 10, 'CHECK VOUCHER', 0, 1, 'C');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(175.8, 10, 'CV NO: ' . $check->cv_no, 0, 1, 'R');
        $pdf->Ln(5);

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
        $textLeft = 'Payee: ' . $check->payee_name;
        $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 0, 'L');

        // Text to be displayed on the right
        $textRight = 'Ref No: ' . $check->ref_no;
        $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textRight, 0, 1, 'L');

        // Text to be displayed on the left
        $textLeft = 'Address: ' . $check->payee_address;
        $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 0, 'L');

        // Text to be displayed on the right
        $textRight = 'Check Date: ' . $check->write_check_date;
        $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textRight, 0, 1, 'L');

        // Text to be displayed on the left
        $textLeft = '';
        $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textLeft, 0, 0, 'L');

        // Text to be displayed on the left
        $textRight = 'Check No: ' . $check->check_no;
        $pdf->Cell($maxWidth / 2 - $padding, $lineHeight, $textRight, 0, 1, 'L');

        $pdf->Ln(2);

        // Table headers

        // Center align the table
        $pdf->Ln(); // Move to the next line
        $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line

        $pdf->Cell(30, 10, 'Code', 0, 0, 'L');
        $pdf->Cell(50, 10, 'Account', 0, 0, 'L');
        $pdf->Cell(50, 10, 'Description', 0, 0, 'L');
        $pdf->Cell(30, 10, 'Debit', 0, 0, 'R');
        $pdf->Cell(30, 10, 'Credit', 0, 0, 'R');
        $pdf->Ln(); // Move to the next line
        $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY()); // Draw a line


        // Initialize total debit and credit
        $totalDebit = 0;
        $totalCredit = 0;

        // Fetch transaction entries for the check
        $transactionEntries = WriteCheck::findTransactionEntries($checkId);
        foreach ($transactionEntries as $entry) {
            // Skip the row if both debit and credit are zero
            if ($entry['debit'] == 0 && $entry['credit'] == 0) {
                continue;
            }

            // Increment total debit and credit
            $totalDebit += $entry['debit'];
            $totalCredit += $entry['credit'];

            // Determine if the account_name needs to be indented
            $indent = ($entry['credit'] != 0) ? '       ' : ''; // Using spaces for indentation if credit is not zero


            // Center align the table
            $pdf->Cell(30, 10, $entry['account_code'], 0, 0, 'L');
            $pdf->Cell(50, 10, $indent . $entry['account_name'], 0, 0, 'L'); // Indent account_name if account_type is Bank
            $pdf->Cell(50, 10, '', 0, 0, 'C'); // This cell has no border
            $pdf->Cell(30, 10, ($entry['debit'] != 0 ? number_format($entry['debit'], 2) : ''), 0, 0, 'R');
            $pdf->Cell(30, 10, ($entry['credit'] != 0 ? number_format($entry['credit'], 2) : ''), 0, 0, 'R');

            $pdf->Ln(5);
        }
        // Add a row for total debit and credit

        $pdf->SetFont('Arial', 'B', 10); // 'B' indicates bold
        $pdf->Cell(130, 10, 'TOTAL:', 0, 0, 'R');
        $pdf->Cell(30, 10, 'P' . number_format($totalDebit, 2), 0, 0, 'R');
        $pdf->Cell(30, 10, 'P' . number_format($totalCredit, 2), 0, 0, 'R');
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
        $pdf->MultiCell($cellWidth, $lineHeight, 'Memo: ' . $check->memo, 0, 'L', false);

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
        // Handle the case where the check ID is invalid or not found
        echo "Check not found!";
    }
} catch (Exception $ex) {
    // Handle any exceptions
    echo "Error: " . $ex->getMessage();
}