<?php

require_once __DIR__ . '/../../_init.php';
require __DIR__ . '/../../vendor/autoload.php';

//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $uom = Uom::find($id);

    if ($uom) {
        $uom->delete();
        flashMessage('delete_uom', 'Uom deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_uom', 'Invalid category', FLASH_ERROR);
    }
    redirect('../../admin_uom.php');
}


//Add category
if (post('action') === 'add') {

    $name = post('name');

    try {
        Uom::add($name);
        flashMessage('add_uom', 'Uom added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_uom', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_uom.php');
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
            // Check if the name already exists
            if (!Uom::findByName($row['name'])) {
                // Assuming your Excel columns map to properties of ChartOfAccount class
                Uom::add($row['name']);
            }
        }

        redirect('../../admin_uom.php'); // You may need to adjust the redirect URL
    } else {
        redirect('../../admin_uom.php'); // You may need to adjust the redirect URL
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
            'name' => $rowData[0],
        ];
    }

    return $data;
}