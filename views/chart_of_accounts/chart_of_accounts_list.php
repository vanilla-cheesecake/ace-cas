<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$chart_of_accounts = ChartOfAccount::all();

$chart_of_account = null;
if (get('action') === 'update') {
    $chart_of_account = ChartOfAccount::find(get('id'));
}

$page = 'chart_of_accounts'; // Set the variable corresponding to the current page
?>



<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Chart of Accounts</strong></h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_coa') ?>
                    <?php displayFlashMessage('delete_coa') ?>
                    <?php displayFlashMessage('update_category') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#coaModal">
                                Add New Account
                            </button>
                            <br><br><br>
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>Account Code</th>
                                        <th>Account Name</th>
                                        <th>Account Type</th>
                                        <th>Description</th>
                                        <th>Sub Account of</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($chart_of_accounts as $chart_of_account): ?>
                                        <tr>
                                            <td><?= $chart_of_account->account_code ?></td>
                                            <td><?= $chart_of_account->accountName ?></td>
                                            <td><?= $chart_of_account->account_type ?></td>
                                            <td><?= $chart_of_account->description ?></td>
                                            <td><?= $chart_of_account->subAccount ?></td>

                                            <td>
                                                <a class="text-primary"
                                                    href="?action=update&id=<?= $chart_of_account->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/chart_of_account_controller.php?action=delete&id=<?= $chart_of_account->id ?>">
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
    <div class="modal fade" id="coaModal" tabindex="-1" role="dialog" aria-labelledby="coaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="coaModalLabelLabel">
                        New Chart of Account
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/chart_of_account_controller.php" id="coaForm">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="categoryId" value="" />
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Account Type -->

                                <div class="form-group">
                                    <label for="accounttype">Account Type</label>
                                    <select class="form-control" id="accounttype" name="accounttype">
                                        <option value="">-- Select Account Type --</option>
                                        <option value="Accounts Payable">ACCOUNTS PAYABLE</option>
                                        <option value="Accounts Receivable">ACCOUNTS RECEIVABLE</option>
                                        <option value="Other Current Assets">OTHER CURRENT ASSETS</option>
                                        <option value="Other Current Liabilities">OTHER CURRENT LIABILITIES</option>
                                        <option value="Other Expense">OTHER EXPENSE</option>
                                        <option value="Other Income">OTHER INCOME</option>
                                        <option value="Fixed Assets">FIXED ASSETS</option>
                                        <option value="Long-term Liabilities">LONG-TERM LIABILITES</option>
                                        <option value="Cost of Goods Sold">COST OF GOODS SOLD</option>
                                        <option value="Equity">EQUITY</option>
                                        <option value="Expenses">EXPENSES</option>
                                        <option value="Income">INCOME</option>
                                        <option value="Non-current Liabilities">NON-CURRENT LIABILITIES</option>
                                        <option value="Bank">BANK</option>
                                        <option value="Other Non-current Liabilities">OTHER NON-CURRENT LIABILITIES
                                        </option>
                                    </select>
                                </div>

                                <!-- Account Code -->
                                <div class="form-group">
                                    <label for="accountCode">Account Code</label>
                                    <input type="text" class="form-control" id="accountCode" name="accountCode"
                                        placeholder="Enter Account Code" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <!-- Set the retrieved sub-account IDs in your dropdown -->
                                <div class="form-group">
                                    <label for="subaccountof">Sub Account of</label>
                                    <select class="form-control" id="subaccountof" name="subaccountof">
                                        <option value="">-- Select Sub Account --</option>
                                        <?php foreach ($chart_of_accounts as $chart_of_account): ?>
                                            <option value="<?= $chart_of_account->accountName ?>">
                                                <?= $chart_of_account->accountName ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Account Name -->
                                <div class="form-group">
                                    <label for="accountname">Account Name</label>
                                    <input type="text" class="form-control" id="accountname" name="accountname"
                                        placeholder="Enter Account Name">
                                </div>
                            </div>
                            <div class="col-md-4">

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">DESCRIPTION</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Enter Description"></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="saveAccountButton">Save Account</button>
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
                "responsive": true,
                "lengthChange": true, // Allow changing the number of rows displayed
                "lengthMenu": [500], // Define the options
                "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "colvis"],
            }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
        });
    </script>