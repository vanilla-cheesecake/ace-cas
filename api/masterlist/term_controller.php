<?php

require_once __DIR__ . '/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $term = Term::find($id);

    if ($term) {
        $term->delete();
        flashMessage('delete_term', 'Term deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_term', 'Invalid term', FLASH_ERROR);
    }
    redirect('../../admin_terms.php');
}


//Add category
if (post('action') === 'add') {

    $term_name = $_POST['term_name'];
    $term_days_due = $_POST['term_days_due'];
    $description = $_POST['description'];


    try {
        Term::add($term_name, $term_days_due, $description);
        flashMessage('add_term', 'Term added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_term', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_terms.php');
}


//Update category

if (post('action') === 'update') {
    $name = post('name');
    $id = post('id');

    try {
        $category = Uom::find($id);
        $category->name = $name;
        $category->update();
        flashMessage('update_uom', 'Uom updated successfully.', FLASH_SUCCESS);
        redirect('../admin_category.php');
    } catch (Exception $ex) {
        flashMessage('update_uom', $ex->getMessage(), FLASH_ERROR);
        redirect("../admin_category.php?action=update&id={$id}");
    }
}