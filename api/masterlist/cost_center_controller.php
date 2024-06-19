<?php

require_once __DIR__ . '/../../_init.php';
require __DIR__ . '/../../vendor/autoload.php';

//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $cost_center = CostCenter::find($id);

    if ($cost_center) {
        $cost_center->delete();
        flashMessage('delete_cost_center', 'Cost center deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_cost_center', 'Invalid cost center', FLASH_ERROR);
    }
    redirect('../../admin_cost_center.php');
}


//Add category
if (post('action') === 'add') {

    $code = $_POST['code'];
    $particular = $_POST['particular'];

    try {
        CostCenter::add($code, $particular);
        flashMessage('add_cost_center', 'Cost center added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_cost_center', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_cost_center.php');
}


//Update category

// if (post('action') === 'update') {
//     $name = post('name');
//     $id = post('id');

//     try {
//         $category = Uom::find($id);
//         $category->name = $name;
//         $category->update();
//         flashMessage('update_uom', 'Uom updated successfully.', FLASH_SUCCESS);
//         redirect('../admin_category.php');
//     } catch (Exception $ex) {
//         flashMessage('update_uom', $ex->getMessage(), FLASH_ERROR);
//         redirect("../admin_category.php?action=update&id={$id}");
//     }
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
        $tmpFilePath = $_FILES['excelFile']['tmp_name'];
        $excelData = readExcelData($tmpFilePath);

        // Loop through Excel data and add accounts
        foreach ($excelData as $row) {
            // Assuming your Excel columns map to properties of ChartOfAccount class
            CostCenter::add(
                $row['code'],
                $row['particular'],
            );
        }

        redirect('../../admin_cost_center.php'); // You may need to adjust the redirect URL
    } else {
        redirect('../../admin_cost_center.php'); // You may need to adjust the redirect URL
    }
}

function readExcelData($filePath)
{
    // You can use libraries like PHPExcel, PHPSpreadsheet, or other suitable libraries to read Excel data
    // Here's a simple example using PHPSpreadsheet
//    require_once 'vendor/autoload.php'; // Assuming you have installed PHPSpreadsheet via Composer

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
    $spreadsheet = $reader->load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $data = [];

    for ($row = 2; $row <= $highestRow; ++$row) {
        $rowData = [];
        for ($col = 'A'; $col <= $highestColumn; ++$col) {
            $cellValue = $worksheet->getCell($col . $row)->getValue();
            $rowData[] = $cellValue;
        }
        // Assuming the column order in Excel matches the property order in ChartOfAccount class
        $data[] = [
            'code' => $rowData[0],
            'particular' => $rowData[1],
        ];
    }

    return $data;
}