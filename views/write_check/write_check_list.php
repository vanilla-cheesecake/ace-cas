<?php
//Guard
include '_guards.php';

Guard::adminOnly();
$accounts = ChartOfAccount::all();
$vendors = Vendor::all();
$customers = Customer::all();
$other_names = OtherNameList::all();
$cost_centers = CostCenter::all();
$discounts = Discount::all();
$wtaxes = WithholdingTax::all();
$sales_taxes = SalesTax::all();
$input_vats = InputVat::all();
$checks = WriteCheck::all();

$page = 'write_check'; // Set the variable corresponding to the current page
?>




<?php include 'views/templates/header.php' ?>
<?php include 'views/templates/sidebar.php' ?>
<div class="main">
    <?php include 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3"><strong>Write Check</strong> Expense</h1>
            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_write_check') ?>
                    <?php displayFlashMessage('delete_check') ?>
                    <?php displayFlashMessage('view_check') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">

                            <a href="create_check" class="btn btn-secondary">New Check</a>
                            <br><br><br>
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Check No</th>
                                        <th>Payee</th>
                                        <th>Total Amount Due</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($checks as $check): ?>
                                        <tr>
                                            <td><?= $check->id ?></td>
                                            <td><?= $check->write_check_date ?></td>
                                            <td><?= $check->check_no ?></td>
                                            <td><?= $check->payee_name ?></td>

                                            <td><b>₱<?= number_format($check->total_amount_due, 2, '.', ',') ?></b></td>
                                            <td>
                                                <a class="text-primary"
                                                    href="view_check?action=update&id=<?= $check->id ?>">
                                                    <i class="fas fa-edit"></i> View
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </main>


    <?php require 'views/templates/footer.php' ?>

    <script>


        //                                 
        // THIS FOR VIEW, $_GETS THE ID THE PASSED THRU DESIRED LOCATION
        $("#table tbody").on("click", "tr", function () {
            // Get the id from the first cell of the clicked row
            var is = $(this).find("td:first").text();
            // Redirect to the redirect to the desired page with parameter id
            window.location.href = "view_check?id=" + is;
        });


        // THIS DATATABLES JQUERY INITIALIZATION
        $(function () {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": true, // Allow changing the number of rows displayed
                "lengthMenu": [10, 20, 50, 100], // Define the options
                "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "colvis"],
            }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>