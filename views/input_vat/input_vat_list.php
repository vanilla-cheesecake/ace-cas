<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$input_vats = InputVat::all();
$accounts = ChartOfAccount::all();

$input_vat = null;
if (get('action') === 'update') {
    $input_vats = InputVat::find(get('id'));
}

$page = 'uom_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Input</strong> Vat</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_input_vat') ?>
                    <?php displayFlashMessage('delete_sales_tax') ?>
                    <?php displayFlashMessage('update_sales_tax') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                Add New Input Vat
                            </button>
                            <br /><br /><br />
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>Input Vat</th>
                                        <th>Input Vat Rate (%)</th>
                                        <th>Description</th>
                                        <th>Account</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($input_vats as $input_vat): ?>
                                        <tr>
                                            <td><?= $input_vat->input_vat_name ?></td>
                                            <td><?= $input_vat->input_vat_rate ?></td>
                                            <td><?= $input_vat->description ?></td>
                                            <td><?= $input_vat->input_vat_account_name ?></td>

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



    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">
                        New Input Vat
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/input_vat_controller.php" id="salesTaxForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="termId" value="" />
                        <div class="form-group">
                            <label for="input_vat_name">Input Vat</label>
                            <input type="text" class="form-control" id="input_vat_name" name="input_vat_name"
                                placeholder="Enter input vat here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="input_vat_rate">Input Vat Rate (%)</label>
                            <input type="number" class="form-control" id="input_vat_rate" name="input_vat_rate"
                                step="0.01" placeholder="Enter input vat rate here" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter description here" required="true" />
                        </div>
                        <!-- Set the retrieved COGS account types in your dropdown -->
                        <div class="form-group">
                            <label for="input_vat_account_id">Input Vat ACCOUNT</label>
                            <select class="form-control" id="sales_tax_account_id" name="input_vat_account_id">
                                <!-- <option value="">-- Select COGS Account --</option> -->
                                <?php foreach ($accounts as $account): ?>

                                    <option value="<?= $account->id ?>"><?= $account->account_code ?> -
                                        <?= $account->description ?>
                                    </option>

                                <?php endforeach; ?>
                            </select>
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

    <script>
        $(function () {
            $("#table").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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