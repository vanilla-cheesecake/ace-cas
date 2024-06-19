<?php

require_once __DIR__ . '/../../_init.php';

class Customer
{
    public $id;
    public $customer_name;
    public $customer_code;
    public $customer_address;
    public $contact_number;
    public $customer_email;
    public $customer_tin;
    public $customer_business_style;
    private static $cache = null;

    public function __construct($customer)
    {
        $this->id = $customer['id'];
        $this->customer_name = $customer['customer_name'];
        $this->customer_code = $customer['customer_code'];
        $this->customer_address = $customer['customer_address'];
        $this->contact_number = $customer['contact_number'];
        $this->customer_email = $customer['customer_email'];
        $this->customer_tin = $customer['customer_tin'];
        $this->customer_business_style = $customer['customer_business_style'];
    }

    // public function update() 
    // {
    //     global $connection;
    //     //Check if name is unique
    //     $category = self::findByName($this->name);
    //     if ($category && $category->id !== $this->id) throw new Exception('Name already exists.');

    //     $stmt = $connection->prepare('UPDATE categories SET name=:name WHERE id=:id');
    //     $stmt->bindParam('name', $this->name);
    //     $stmt->bindParam('id', $this->id);
    //     $stmt->execute();
    // }

    public function delete()
    {
        global $connection;

        $stmt = $connection->prepare('DELETE FROM `customers` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('SELECT * FROM `customers`');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($item) {
            return new Customer($item);
        }, $result);

        return static::$cache;
    }

    public static function add($customer_name, $customer_code, $customer_address, $contact_number, $customer_email, $customer_tin, $customer_business_style)
    {
        global $connection;

        // Check if the vendor name already exists
        if (static::findByName($customer_name)) {
            throw new Exception('Customer name already exists');
        }

        // Prepare the SQL query
        $stmt = $connection->prepare('INSERT INTO `customers` (customer_name, customer_code, customer_address, contact_number, customer_email, customer_tin, customer_business_style) 
        VALUES (:customer_name, :customer_code, :customer_address, :contact_number, :customer_email, :customer_tin, :customer_business_style)');

        // Bind parameters
        $stmt->bindParam(":customer_name", $customer_name);
        $stmt->bindParam(":customer_code", $customer_code);
        $stmt->bindParam(":customer_address", $customer_address);
        $stmt->bindParam(":contact_number", $contact_number);
        $stmt->bindParam(":customer_email", $customer_email);
        $stmt->bindParam(":customer_tin", $customer_tin);
        $stmt->bindParam(":customer_business_style", $customer_business_style);

        // Execute the query
        $stmt->execute();
    }

    public static function findByName($name)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `customers` WHERE customer_name=:name");
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (!empty($result)) {
            return new Customer($result[0]);
        }

        return null;
    }

    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `customers` WHERE id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new Customer($result[0]);
        }

        return null;
    }
}