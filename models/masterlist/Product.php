<?php

require_once __DIR__ . '/../../_init.php';
class Product
{
  public $id;
  public $item_name;
  public $itemCode;
  public $itemType;
  public $preferredVendorId;
  public $reorderPoint;
  public $category_id;
  public $salesDescription;
  public $price;
  public $purchase_description;
  public $costPrice;
  public $uom_id;
  public $quantity;
  public $itemCogsAccountId;
  public $itemIncomeAccountId;
  public $itemAssetsAccountId;

  public $category_name;
  public $category;

  public function __construct($product)
  {
    $this->id = $product['id'];
    $this->item_name = isset($product['item_name']) ? $product['item_name'] : '';
    $this->itemCode = isset($product['item_code']) ? $product['item_code'] : '';
    $this->itemType = isset($product['item_type']) ? $product['item_type'] : '';
    $this->preferredVendorId = isset($product['vendor_name']) ? $product['vendor_name'] : '';
    $this->reorderPoint = isset($product['reorder_point']) ? intval($product['reorder_point']) : 0;
    $this->category_id = isset($product['category_id']) ? $product['category_id'] : '';
    $this->category = $this->getCategory($product);
    $this->salesDescription = isset($product['sales_description']) ? $product['sales_description'] : '';
    $this->price = isset($product['selling_price']) ? floatval($product['selling_price']) : 0.0;
    $this->purchase_description = isset($product['purchase_description']) ? $product['purchase_description'] : '';
    $this->costPrice = isset($product['cost_price']) ? floatval($product['cost_price']) : 0.0;
    $this->uom_id = isset($product['uom_name']) ? $product['uom_name'] : '';
    $this->quantity = isset($product['quantity']) ? intval($product['quantity']) : 0;
    $this->itemCogsAccountId = isset($product['itemcogsaccount_name']) ? $product['itemcogsaccount_name'] : '';
    $this->itemIncomeAccountId = isset($product['itemincomeaccount_name']) ? $product['itemincomeaccount_name'] : '';
    $this->itemAssetsAccountId = isset($product['itemassetsaccount_name']) ? $product['itemassetsaccount_name'] : '';
    // Other assignments...
    $this->category_name = isset($product['category_name']) ? $product['category_name'] : '';
  }



  public static function all()
  {
    global $connection;

    $stmt = $connection->prepare('SELECT 
                                    items.*, 
                                    categories.name AS category_name,
                                    vendors.vendor_name AS vendor_name,
                                    uom.name AS uom_name,
                                    cogs.description AS itemcogsaccount_name,
                                    income.description AS itemincomeaccount_name,
                                    assets.description AS itemassetsaccount_name
                                  FROM 
                                    items 
                                  INNER JOIN 
                                    categories ON items.category_id = categories.id
                                  INNER JOIN 
                                    vendors ON items.vendor_id = vendors.id
                                  INNER JOIN 
                                    uom ON items.uom_id = uom.id
                                  INNER JOIN 
                                    chart_of_account AS cogs ON items.itemcogsaccount_id = cogs.id
                                  INNER JOIN 
                                    chart_of_account AS income ON items.itemincomeaccount_id = income.id
                                  INNER JOIN 
                                    chart_of_account AS assets ON items.itemassetsaccount_id = assets.id');
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $result = $stmt->fetchAll();

    $result = array_map(fn($item) => new Product($item), $result);

    return $result;
  }

  public static function add(
    $vendor_id,
    $category_id,
    $uom_id,
    $itemcogsaccount_id,
    $itemincomeaccount_id,
    $itemassetsaccount_id,
    $item_name,
    $item_code,
    $item_type,
    $preferred_vendor,
    $reorder_point,
    $sales_description,
    $selling_price,
    $purchase_description,
    $cost_price,
    $quantity
  ) {
    global $connection;

    $sql_command = 'INSERT INTO items (vendor_id, category_id, uom_id, itemcogsaccount_id, itemincomeaccount_id, itemassetsaccount_id, item_name, item_code, item_type, reorder_point, sales_description, selling_price, purchase_description, cost_price, quantity) 
        VALUES (:vendor_id, :category_id, :uom_id, :itemcogsaccount_id, :itemincomeaccount_id, :itemassetsaccount_id, :item_name, :item_code, :item_type, :reorder_point, :sales_description, :selling_price, :purchase_description, :cost_price, :quantity)';

    $stmt = $connection->prepare($sql_command);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':uom_id', $uom_id);
    $stmt->bindParam(':itemcogsaccount_id', $itemcogsaccount_id);
    $stmt->bindParam(':itemincomeaccount_id', $itemincomeaccount_id);
    $stmt->bindParam(':itemassetsaccount_id', $itemassetsaccount_id);
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':item_code', $item_code);
    $stmt->bindParam(':item_type', $item_type);
    $stmt->bindParam(':reorder_point', $reorder_point);
    $stmt->bindParam(':sales_description', $sales_description);
    $stmt->bindParam(':selling_price', $selling_price);
    $stmt->bindParam(':purchase_description', $purchase_description);
    $stmt->bindParam(':cost_price', $cost_price);
    $stmt->bindParam(':quantity', $quantity);

    $stmt->execute();
  }

  public function update()
  {
    global $connection;

    $sql_command = 'UPDATE items SET 
                        vendor_id = :preferredVendorId,
                        category_id = :category_id,
                        uom_id = :uom_id,
                        itemcogsaccount_id = :itemCogsAccountId,
                        itemincomeaccount_id = :itemIncomeAccountId,
                        itemassetsaccount_id = :itemAssetsAccountId,
                        item_name = :name,
                        item_code = :itemCode,
                        item_type = :itemType,
                        reorder_point = :reorderPoint,
                        sales_description = :salesDescription,
                        selling_price = :price,
                        purchase_description = :purchaseDescription,
                        cost_price = :costPrice,
                        quantity = :quantity
                        WHERE id = :id';

    $stmt = $connection->prepare($sql_command);
    $stmt->bindParam(':preferredVendorId', $this->preferredVendorId);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':uom_id', $this->uom_id);
    $stmt->bindParam(':itemCogsAccountId', $this->itemCogsAccountId);
    $stmt->bindParam(':itemIncomeAccountId', $this->itemIncomeAccountId);
    $stmt->bindParam(':itemAssetsAccountId', $this->itemAssetsAccountId);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':itemCode', $this->itemCode);
    $stmt->bindParam(':itemType', $this->itemType); // Corrected parameter name
    $stmt->bindParam(':reorderPoint', $this->reorderPoint); // Corrected parameter name
    $stmt->bindParam(':salesDescription', $this->salesDescription);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':purchaseDescription', $this->purchaseDescription);
    $stmt->bindParam(':costPrice', $this->costPrice);
    $stmt->bindParam(':quantity', $this->quantity);
    $stmt->bindParam(':id', $this->id);

    $stmt->execute();
  }

  public function delete()
  {
    global $connection;

    $stmt = $connection->prepare('DELETE FROM `items` WHERE id=:id');
    $stmt->bindParam('id', $this->id);
    $stmt->execute();
  }

  private function getCategory($product)
  {
    if (isset($product['category_name'])) {
      return new Category([
        'id' => $product['category_id'],
        'name' => $product['category_name']
      ]);
    }

    return Category::find($product['category_id']);
  }


  public static function find($id)
  {
    global $connection;

    $stmt = $connection->prepare('SELECT 
                                        items.*, 
                                        categories.name AS category_name,
                                        vendors.vendor_name AS vendor_name,
                                        uom.name AS uom_name,
                                        cogs.account_name AS itemcogsaccount_name,
                                        income.account_name AS itemincomeaccount_name,
                                        assets.account_name AS itemassetsaccount_name
                                      FROM 
                                        items 
                                      INNER JOIN 
                                        categories ON items.category_id = categories.id
                                      INNER JOIN 
                                        vendors ON items.vendor_id = vendors.id
                                      INNER JOIN 
                                        uom ON items.uom_id = uom.id
                                      INNER JOIN 
                                        chart_of_account AS cogs ON items.itemcogsaccount_id = cogs.id
                                      INNER JOIN 
                                        chart_of_account AS income ON items.itemincomeaccount_id = income.id
                                      INNER JOIN 
                                        chart_of_account AS assets ON items.itemassetsaccount_id = assets.id
                                      WHERE 
                                        items.id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $result = $stmt->fetch();

    if ($result) {
      return new Product($result);
    }

    return null;
  }
}