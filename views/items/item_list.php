<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();

$categories = Category::all();
$vendors = Vendor::all();
$uoms = Uom::all();
$accounts = ChartOfAccount::all();
$products = Product::all();

$page = 'item_list';

// Define the list of item types
$itemTypes = [
    "Inventory",
    "Non-inventory",
    "Work in Process",
    "Service",
    "Finished Goods",
    "Subtotal",
    "Discount",
    "Tax Type",
    "Group",
    "Payment"
];

// Sort the item types alphabetically
sort($itemTypes);

?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Item</strong> List</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_product') ?>
                    <?php displayFlashMessage('delete_product') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">

                            <!-- Button to trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#productModal">
                                Add New item
                            </button>
                            <br /><br /><br />
                            <table id="productTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Qty</th>
                                        <th>UOM</th>
                                        <th>Selling Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= $product->itemCode ?></td>
                                            <td><?= $product->item_name ?></td>
                                            <td><?= $product->category_name ?></td>
                                            <td><?= $product->quantity ?></td>
                                            <td><?= $product->uom_id ?></td>
                                            <td>₱<?= number_format($product->price, 2, '.', ',') ?></td>
                                            <td>
                                                <a class="text-primary" href="?action=update&id=<?= $product->id ?>">
                                                    <i class="fas fa-edit"></i> Update
                                                </a>
                                                <a class="text-danger ml-2"
                                                    href="api/masterlist/product_controller.php?action=delete&id=<?= $product->id ?>">
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
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">
                        NEW ITEM
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="api/masterlist/product_controller.php?action=add">
                        <input type="hidden" name="action" id="modalAction" value="add" />
                        <input type="hidden" name="id" id="itemId" value="" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="item_name">Item Name</label>
                                    <input type="text" class="form-control" id="item_name" name="item_name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_code">Code</label>
                                    <input type="text" class="form-control" id="item_code" name="item_code">
                                </div>
                                <div class="form-group">
                                    <label for="item_type">Type</label>
                                    <select class="form-control" id="item_type" name="item_type">
                                        <?php foreach ($itemTypes as $type): ?>
                                            <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preferred_vendor_id">Preffered Vendor</label>
                                    <select id="preferred_vendor_id" class="form-control" name="preferred_vendor_id">
                                        <option value="">-- Select Vendor --</option>
                                        <?php foreach ($vendors as $vendor): ?>
                                            <option value="<?= $vendor->id ?>"><?= $vendor->vendor_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="reorder_point">Reorder Point</label>
                                    <input type="number" class="form-control" id="reorder_point" name="reorder_point"
                                        value="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" class="form-control" name="category_id">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->id ?>"><?= $category->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sales_description">Description</label>
                                    <input class="form-control" id="sales_description" name="sales_description"></input>
                                </div>
                                <div class="form-group">
                                    <label for="selling_price">Selling Price</label>
                                    <input type="number" class="form-control" id="selling_price" name="selling_price"
                                        step=".25">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="purchase_description">Purchase Description</label>
                                    <input type="text" class="form-control" id="purchase_description"
                                        name="purchase_description">
                                </div>
                                <div class="form-group">
                                    <label for="cost_price">Cost Price</label>
                                    <input type="number" class="form-control" id="cost_price" name="cost_price"
                                        step=".25">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="uom_id">Uom</label>
                                    <select id="uom_id" class="form-control" name="uom_id">
                                        <?php foreach ($uoms as $uom): ?>
                                            <option value="<?= $uom->id ?>"><?= $uom->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Initial Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" required
                                        min="0" value="1">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Set the retrieved COGS account types in your dropdown -->
                                <div class="form-group">
                                    <label for="itemcogsaccount_id">COGS ACCOUNT</label>
                                    <select class="form-control" id="itemcogsaccount_id" name="itemcogsaccount_id">
                                        <!-- <option value="">-- Select COGS Account --</option> -->
                                        <?php foreach ($accounts as $account): ?>
                                            <?php if ($account->account_type == 'Cost of Goods Sold'): ?>
                                                <option value="<?= $account->id ?>">
                                                    <?= $account->account_code ?>-<?= $account->description ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">

                                <!-- Set the retrieved COGS account types in your dropdown -->
                                <div class="form-group">
                                    <label for="itemincomeaccount_id">INCOME ACCOUNT</label>
                                    <select class="form-control" id="itemincomeaccount_id" name="itemincomeaccount_id">
                                        <!-- <option value="">-- Select Income Account --</option> -->
                                        <?php foreach ($accounts as $account): ?>
                                            <?php if ($account->account_type == 'Income'): ?>
                                                <option value="<?= $account->id ?>">
                                                    <?= $account->account_code ?>-<?= $account->description ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Set the retrieved COGS account types in your dropdown -->
                                <div class="form-group">
                                    <label for="itemassetsaccount_id">ASSETS ACCOUNT</label>
                                    <select class="form-control" id="itemassetsaccount_id" name="itemassetsaccount_id">
                                        <!-- <option value="">-- Select Assets Account --</option> -->
                                        <?php foreach ($accounts as $account): ?>
                                            <?php if ($account->account_type == 'Other Current Assets' || 'Other Assets'): ?>
                                                <option value="<?= $account->id ?>">
                                                    <?= $account->account_code ?>-<?= $account->description ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="mt-16">
                            <button class="btn btn-primary w-full" type="submit">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require 'views/templates/footer.php' ?>

    <script>
        $(function () {
            $("#productTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false, "pageLength": 100,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#productTable_wrapper .col-md-6:eq(0)');
        });

    </script>