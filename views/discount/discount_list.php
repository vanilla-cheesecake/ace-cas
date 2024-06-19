<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$discounts = Discount::all();
$accounts = ChartOfAccount::all();
$discount = null;
if (get('action') === 'update') {
    $discount = Discount::find(get('id'));
}

$page = 'disount_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Discount</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_discount') ?>
                    <?php displayFlashMessage('delete_discount') ?>
                    <?php displayFlashMessage('update_discount') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                Add New Discount
                            </button>
                            <br /><br /><br />
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>Discount</th>
                                        <th>Discount Rate</th>
                                        <th>Description</th>
                                        <th>Account</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($discounts as $discount): ?>
                                        <tr>
                                            <td><?= $discount->discount_name ?></td>
                                            <td><?= $discount->discount_rate ?></td>
                                            <td><?= $discount->description ?></td>
                                            <td><?= $discount->discount_account_name ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $discount->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/discount_controller.php?action=delete&id=<?= $discount->id ?>">
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
                        New Discount
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/discount_controller.php" id="salesTaxForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="discountId" value="" />
                        <div class="form-group">
                            <label for="discount_name">Discount Name</label>
                            <input type="text" class="form-control" id="discount_name" name="discount_name"
                                placeholder="Enter discount here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="discount_rate">Discount Rate (%)</label>
                            <input type="number" class="form-control" id="discount_rate" name="discount_rate"
                                placeholder="Enter discount rate here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Enter description here" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="discount_account_id">DISCOUNT ACCOUNT</label>
                            <select class="form-control" id="discount_account_id" name="discount_account_id">
                                <!-- <option value="">-- Select COGS Account --</option> -->
                                <?php foreach ($accounts as $account): ?>
                                    <?php //if ($account->gl_code === '8001'): ?>
                                    <option value="<?= $account->id ?>"><?= $account->description ?></option>
                                    <?php //endif; ?>
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
                "buttons": ["csv", "excel", "pdf", "print", "colvis"]
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