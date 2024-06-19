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

            <h1 class="h3 mb-3"><strong>Pay</strong> Bills</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_uom') ?>
                    <?php displayFlashMessage('delete_uom') ?>
                    <?php displayFlashMessage('update_uom') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                        <div class="dropdown">
                                <button class="btn btn-secondary" type="button" id="newTransactionDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    onclick="openModal()">
                                    Pay Bills
                                </button>
                            </div>  
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </main>



    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="uomModal" tabindex="-1" role="dialog" aria-labelledby="uomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uomModalLabel">
                        New Uom
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/uom_controller.php" id="uomForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="uomId" value="" />
                        <div class="form-group">
                            <label for="categoryName">Uom</label>
                            <input type="text" class="form-control" id="categoryName" name="name"
                                placeholder="Enter category name here" required="true" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php require 'views/templates/footer.php' ?>

     <!-- Modal -->
    <!-- EXPENSE MODAL -->
    <div id="modal-container">
        <?php include 'modals/pay_bills.php'; ?>
    </div>


    <?php require 'templates/footer.php' ?>

    <script>
        function openModal() {
            $('#myModal').modal('show');
        }
    </script>

