<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';

$uoms = Uom::all();

$uom = null;
if (get('action') === 'update') {
    $uom = Uom::find(get('id'));
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

            <h1 class="h3 mb-3"><strong>General</strong> Journal</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_uom') ?>
                    <?php displayFlashMessage('delete_uom') ?>
                    <?php displayFlashMessage('update_uom') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">

                            <form id="enterBillsForm" action="" name="enterBillsForm" method="POST">
                                <div class="form-row">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="entry_date">ENTRY DATE</label>
                                            <div class="input-group">
                                                <?php
                                                $currentDate = new DateTime('now', new DateTimeZone('Asia/Manila'));
                                                $formattedDate = $currentDate->format('Y-m-d');
                                                ?>
                                                <input type="date" class="form-control" id="entry_date"
                                                    name="entry_date" value="<?php echo $formattedDate; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 offset-md-2">
                                            <!-- Empty div with offset -->
                                        </div>
                                        <br>
                                        <div class="form-group col-md-2">
                                            <label for="entry_no">ENTRY NO.</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="entry_no" name="entry_no"
                                                    placeholder="Entry No" required>
                                            </div>
                                        </div>
                                        <br>
                                        <table class="table table-bordered" id="generalJournalTable">
                                            <thead>
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                    <th>Name</th>
                                                    <th>Memo</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody id="generalJournalTableBody">

                                                <!-- Each row represents a separate item -->
                                                <!-- You can dynamically add rows using JavaScript/jQuery -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th id="totalDebit">0.00</th>
                                                    <th id="totalCredit">0.00</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="col-md-10 d-inline-block">
                                            <button type="button" class="btn btn-success" id="addAccountButton">
                                                <i class="fas fa-plus"></i> Add Account
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <br><br>
                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-md-10 d-inline-block">
                                        <button type="button" class="btn btn-success" id="saveButton">
                                            <i class="fas fa-save"></i> Save General Journal
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="clearButton">
                                            <i class="fas fa-times-circle"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </form>
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
        $("#clearButton").on("click", function () {
            // Clear all input fields in the form
            $("#enterBillsForm")[0].reset();

            // Remove all rows from the table body except the first one
            $("#generalJournalTableBody tr:not(:first)").remove();

            // Update totals to reset them
            updateTotals();
        });
        // Save button event listener
        $("#saveButton").on("click", function () {
            if (validateEntryNo() && validateImbalance()) {
                // Prepare form data
                var formData = $("#enterBillsForm").serialize();

                // Perform the AJAX request to save data
                $.ajax({
                    type: "POST",
                    url: "modules/accounting/save_general_journal.php",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        // Display success/error message
                        Swal.fire({
                            icon: response.status === 'success' ? 'success' : 'error',
                            title: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Redirect to the sales_invoice page after clicking OK if the response is successful
                            if (response.status === 'success') {
                                window.location.href = 'create_general_journal';
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        // Display error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving data.',
                            confirmButtonText: 'OK'
                        });

                        console.log(xhr.responseText); // Log the error response to the console
                    }
                });
            }
        });

        // Function to validate the entry number input
        function validateEntryNo() {
            var entryNo = $("#entry_no").val().trim();
            if (entryNo === '') {
                // Display SweetAlert warning for invalid entry number
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Entry Number',
                    text: 'Please enter a valid entry number.',
                    confirmButtonText: 'OK'
                });
                // Add is-invalid class to the entry number input
                $("#entry_no").addClass("is-invalid");
                return false;
            } else {
                // Remove is-invalid class if entry number is valid
                $("#entry_no").removeClass("is-invalid");
                return true;
            }
        }
        // Add one row by default
        addJournalEntry();

        // Add expense row when the "Add Expense" button is clicked
        $("#addAccountButton").on("click", function () {
            addJournalEntry();
        });

        // Event listener for removing an item
        $("#generalJournalTableBody").on("click", ".removeItemBtn", function () {
            $(this).closest("tr").remove();
            updateTotals();
        });

        // Event listener for input changes
        $("#generalJournalTableBody").on("input", "input[name^='debit'], input[name^='credit']", function () {
            updateTotals();
            validateDebitCreditConsistency();
        });


        function addJournalEntry() {
            // Parse JSON data for chartOfAccount
            var chartOfAccount = <?php echo $chartOfAccountJSON; ?>;
            var options = "";
            chartOfAccount.forEach(function (account) {
                options += '<option value="' + account["account_name"] + '">' + account["account_name"] + '</option>';
            });

            // Parse JSON data for otherNames
            var otherNames = <?php echo $otherNamesJSON; ?>;
            var optionss = "";
            otherNames.forEach(function (account) {
                optionss += '<option value="' + account["account_name"] + '">' + account["account_name"] + ' | ' + account["source"] + '</option>';
            });

            // Create a new row with the select dropdown
            var newRow = '<tr>' +
                '<td><select name="account[]" class="form-control">' + options + '</select></td>' +
                '<td><input type="number" name="debit[]" class="form-control" placeholder="Debit"></td>' +
                '<td><input type="number" name="credit[]" class="form-control" placeholder="Credit"></td>' +
                '<td><select name="name[]" class="form-control">' + optionss + '</select></td>' +
                '<td><input type="text" name="memo[]" class="form-control" placeholder="Memo"></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm removeItemBtn">Remove</button></td>'
            '</tr>';
            // Append the new row to the table
            $("#generalJournalTableBody").append(newRow);
            // Show the remove button for the new row
            $("#generalJournalTableBody tr:last-child .removeItemBtn").show();
            // Update totals
            updateTotals();
        }


        function updateTotals() {
            // For example:
            var totalDebit = 0;
            var totalCredit = 0;

            $('#generalJournalTableBody tr').each(function () {
                var debit = parseFloat($(this).find('td:eq(1) input').val()) || 0;
                var credit = parseFloat($(this).find('td:eq(2) input').val()) || 0;

                totalDebit += debit;
                totalCredit += credit;
            });

            $('#totalDebit').text(totalDebit.toFixed(2));
            $('#totalCredit').text(totalCredit.toFixed(2));
        }

        function validateImbalance() {
            // Check for imbalance
            var totalDebit = parseFloat($('#totalDebit').text()) || 0;
            var totalCredit = parseFloat($('#totalCredit').text()) || 0;

            if (totalDebit !== totalCredit) {
                // Display SweetAlert warning
                Swal.fire({
                    icon: 'error',
                    title: 'Transaction Imbalance',
                    text: 'The total amount in the Debit column must equal the total amount in the Credit column.',
                    confirmButtonText: 'OK'
                });

                return false; // Imbalance detected
            }

            return true; // No imbalance
        }



        function validateDebitCreditConsistency() {
            var inconsistentEntries = [];

            $('#generalJournalTableBody tr').each(function () {
                var account = $(this).find('td:eq(0) select').val();
                var debit = parseFloat($(this).find('td:eq(1) input').val()) || 0;
                var credit = parseFloat($(this).find('td:eq(2) input').val()) || 0;

                if ((debit !== 0 && credit !== 0) && (debit > 0 && credit > 0)) {
                    inconsistentEntries.push(account);
                }
            });

            if (inconsistentEntries.length > 0) {
                // Display warning about inconsistent entries
                var message = 'Inconsistent entries found. The following accounts have both debit and credit entries: ' + inconsistentEntries.join(', ');

                // Display SweetAlert warning
                Swal.fire({
                    icon: 'warning',
                    title: 'Inconsistent Entries',
                    text: message,
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>