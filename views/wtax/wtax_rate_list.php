<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$wtaxes = WithholdingTax::all();
$accounts = ChartOfAccount::all();
$wtax = null;
if (get('action') === 'update') {
    $wtax = WithholdingTax::find(get('id'));
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

            <h1 class="h3 mb-3"><strong>Withholding</strong> Tax</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_witholding_tax') ?>
                    <?php displayFlashMessage('delete_withholding_tax') ?>
                    <?php displayFlashMessage('update_sales_tax') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                Add New Withholding Tax
                            </button>
                            <br /><br /><br />
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>Withholding Tax</th>
                                        <th>Withholding Tax Rate</th>
                                        <th>Description</th>
                                        <th>Account</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($wtaxes as $wtax): ?>
                                        <tr>
                                            <td><?= $wtax->wtax_name ?></td>
                                            <td><?= $wtax->wtax_rate ?></td>
                                            <td><?= $wtax->description ?></td>
                                            <td><?= $wtax->wtax_account_id ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $wtax->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/wtax_controller.php?action=delete&id=<?= $wtax->id ?>">
                                                    <i class="fas fa-trash-alt"></i> Delete
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



    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">
                        New Sales Tax
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/wtax_controller.php" id="salesTaxForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="termId" value="" />
                        <div class="form-group">
                            <label for="wtax_name">Witholding Tax Name</label>
                            <input type="text" class="form-control" id="wtax_name" name="wtax_name"
                                placeholder="Enter withholding tax here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="wtax_rate">Witholding Tax Rate (%)</label>
                            <input type="number" class="form-control" id="wtax_rate" name="wtax_rate"
                                placeholder="Enter withholding tax rate here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter description here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="wtax_account_id">TAX ACCOUNT</label>
                            <select class="form-control" id="wtax_account_id" name="wtax_account_id">
                                <!-- <option value="">-- Select COGS Account --</option> -->
                                <?php foreach ($accounts as $account): ?>
                                    <option value="<?= $account->id ?>"><?= $account->description ?></option>
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