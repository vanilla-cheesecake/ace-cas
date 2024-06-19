<?php

require_once __DIR__.'/../../_init.php';


//Delete category
if (get('action') === 'delete') {
    $id = get('id');
    $location = Location::find($id);

    if ($location){
        $location->delete();
        flashMessage('delete_location', 'Uom deleted successfully', FLASH_SUCCESS);
    } else {
        flashMessage('delete_location', 'Invalid category', FLASH_ERROR);
    }
    redirect('../../admin_location.php');
}


//Add category
if (post('action') === 'add') {

    $name = post('name');

    try {
        Location::add($name);
        flashMessage('add_location', 'Location added successfully.', FLASH_SUCCESS);
    } catch (Exception $ex) {
        flashMessage('add_location', $ex->getMessage(), FLASH_ERROR);
    }

    redirect('../../admin_location.php');
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