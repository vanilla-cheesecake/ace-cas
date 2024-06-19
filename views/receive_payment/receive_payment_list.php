<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$accounts = ChartOfAccount::all();
$vendors = Vendor::all();
$customers = Customer::all();
$other_names = OtherNameList::all();
$cost_centers = CostCenter::all();
$discounts = Discount::all();
$wtaxes = WithholdingTax::all();
$sales_taxes = SalesTax::all();
$input_vats = InputVat::all();

?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Receive</strong> Payment</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_uom') ?>
                    <?php displayFlashMessage('delete_uom') ?>
                    <?php displayFlashMessage('update_uom') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown">
                                <a href="create_receive_payment" class="btn btn-secondary">Receive Payment</a>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
</div>

</main>
<?php require 'views/templates/footer.php' ?>