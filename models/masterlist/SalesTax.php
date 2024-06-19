<?php

require_once __DIR__ . '/../../_init.php';

class SalesTax
{
    public $id;
    public $sales_tax_name;
    public $sales_tax_rate;
    public $description;

    public $sales_tax_account_id;
    private static $cache = null;

    public function __construct($sales_tax)
    {
        $this->id = $sales_tax['id'];
        $this->sales_tax_name = $sales_tax['sales_tax_name'];
        $this->sales_tax_rate = $sales_tax['sales_tax_rate'];
        $this->description = $sales_tax['description'];
        $this->sales_tax_account_id = $sales_tax['account_name'];
    }

    public function update()
    {
        global $connection;
        //Check if name is unique
        $sales_tax = self::findByName($this->sales_tax_name);
        if ($sales_tax && $sales_tax->id !== $this->id)
            throw new Exception('Sales tax already exists.');

        $stmt = $connection->prepare('UPDATE sales_tax SET sales_tax_name=:sales_tax_name WHERE id=:id');
        $stmt->bindParam('sales_tax_name', $this->sales_tax_name);
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public function delete()
    {
        global $connection;

        $stmt = $connection->prepare('DELETE FROM `sales_tax` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('
            SELECT sales_tax.*, chart_of_account.*
            FROM sales_tax
            JOIN chart_of_account
            ON sales_tax.sales_tax_account_id = chart_of_account.id
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($item) {
            return new SalesTax($item);
        }, $result);

        return static::$cache;
    }

    public static function add($sales_tax_name, $sales_tax_rate, $description, $sales_tax_account_id)
    {
        global $connection;

        if (static::findByName($sales_tax_name))
            throw new Exception('Sales tax already exists');

        $stmt = $connection->prepare('INSERT INTO `sales_tax`(sales_tax_name, sales_tax_rate, description, sales_tax_account_id) 
        VALUES (:sales_tax_name, :sales_tax_rate, :description, :sales_tax_account_id)');
        $stmt->bindParam("sales_tax_name", $sales_tax_name);
        $stmt->bindParam("sales_tax_rate", $sales_tax_rate);
        $stmt->bindParam("description", $description);
        $stmt->bindParam("sales_tax_account_id", $sales_tax_account_id);
        $stmt->execute();
    }

    public static function findByName($sales_tax_name)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `sales_tax` WHERE sales_tax_name=:sales_tax_name");
        $stmt->bindParam("sales_tax_name", $sales_tax_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new SalesTax($result[0]);
        }

        return null;
    }

    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `sales_tax` WHERE id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new SalesTax($result[0]);
        }

        return null;
    }
}