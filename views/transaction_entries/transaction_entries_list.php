<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

//require_once 'api/category_controller.php';


$transaction_entries = TransactionEntry::all();

$page = 'uom_list';
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3"><strong>Transaction</strong> Entries</h1>
            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_uom') ?>
                    <?php displayFlashMessage('delete_uom') ?>
                    <?php displayFlashMessage('update_uom') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <!-- SELECT DATE -->
                                    <div class="form-group">
                                        <label for="from_date">From:</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date"
                                            value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <!-- SELECT DATE -->
                                    <div class="form-group">
                                        <label for="to_date">To:</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date"
                                            value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 py-4">
                                    <!-- SELECT DATE -->
                                    <!-- Filter button -->
                                    <button class="btn btn-secondary" id="filter_button">Filter</button>
                                    <button class="btn btn-primary" id="print_button">Print</button>
                                </div>
                            </div>
                            <br /><br />
                            <center>
                                <h3 id="date_range"></h3>
                            </center>

                            <br />
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Ref No</th>
                                        <th>Account</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>

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
        $(document).ready(function () {
            // Print button click event
            // Print button click event
            $('#print_button').click(function () {
                // Hide filter inputs, labels, and buttons
                $('#from_date, #to_date, #filter_button, #print_button, label[for="from_date"], label[for="to_date"]').hide();
                // Trigger print dialog
                window.print();
                // Show filter inputs, labels, and buttons after printing
                $('#from_date, #to_date, #filter_button, #print_button, label[for="from_date"], label[for="to_date"]').show();
            });
            $('#filter_button').click(function () {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();

                // Perform AJAX request to fetch filtered data based on the selected date range
                $.ajax({
                    url: 'api/transaction_entries_controller.php',
                    method: 'POST',
                    data: {
                        from_date: fromDate,
                        to_date: toDate
                    },
                    success: function (response) {
                        // Clear existing table body
                        $('#table tbody').empty();

                        var currentRefNo = null;
                        var totalDebit = 0;
                        var totalCredit = 0;

                        // Check if response is empty
                        if (response.length === 0) {
                            // Append message indicating no transaction entries
                            $('#table tbody').append(
                                '<tr><td colspan="5">No transaction entries found</td></tr>'
                            );
                        } else {
                            // Display date range in h1 element
                            $('#date_range').text('Transaction Entries from ' + formatDate(fromDate) + ' to ' + formatDate(toDate));

                            // Iterate through filtered data
                            $.each(response, function (index, entry) {
                                // Check if reference number has changed
                                if (entry.ref_no !== currentRefNo) {
                                    // If it's not the first row, append total debit and credit for the previous reference number
                                    if (currentRefNo !== null) {
                                        $('#table tbody').append(
                                            '<tr>' +
                                            '<td colspan="4"><strong>Total</strong></td>' +
                                            '<td><strong>₱' + totalDebit.toFixed(2) + '</strong></td>' +
                                            '<td><strong>₱' + totalCredit.toFixed(2) + '</strong></td>' +
                                            '</tr>'
                                        );
                                    }
                                    currentRefNo = entry.ref_no;
                                    totalDebit = 0;
                                    totalCredit = 0;
                                }

                                // Update total debit and credit
                                totalDebit += (entry.debit !== null && parseFloat(entry.debit) !== 0) ? parseFloat(entry.debit) : 0;
                                totalCredit += (entry.credit !== null && parseFloat(entry.credit) !== 0) ? parseFloat(entry.credit) : 0;

                                // Check if either debit or credit is non-zero
                                if ((entry.debit !== null && parseFloat(entry.debit) !== 0) || (entry.credit !== null && parseFloat(entry.credit) !== 0)) {
                                    // Append transaction entry row
                                    $('#table tbody').append(
                                        '<tr>' +
                                        '<td>' + formatDate(entry.date) + '</td>' +
                                        '<td>' + entry.type + '</td>' +
                                        '<td>' + entry.ref_no + '</td>' +
                                        '<td><b>' + (entry.credit ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + entry.account_name : entry.account_name) + '</b></td>' + // Indent account name if it has credit
                                        '<td>' + (entry.debit ? '₱' + parseFloat(entry.debit).toFixed(2) : '') + '</td>' +
                                        '<td>' + (entry.credit ? '₱' + parseFloat(entry.credit).toFixed(2) : '') + '</td>' +
                                        '</tr>'
                                    );
                                }
                            });
                            // Append total debit and credit for the last reference number
                            $('#table tbody').append(
                                '<tr>' +
                                '<td colspan="4"><strong>Total</strong></td>' +
                                '<td><strong>₱' + totalDebit.toFixed(2) + '</strong></td>' +
                                '<td><strong>₱' + totalCredit.toFixed(2) + '</strong></td>' +
                                '</tr>'
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        // Handle error
                    }
                });
            });
        });

        // Function to format date as "Month Day, Year"
        function formatDate(dateString) {
            var date = new Date(dateString);
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }


    </script>