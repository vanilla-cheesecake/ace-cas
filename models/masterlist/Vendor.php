<?php

require_once __DIR__ . '/../../_init.php';

class Vendor
{
    public $id;
    public $vendor_name;
    public $vendor_code;
    public $account_number;
    public $vendor_address;
    public $contact_number;
    public $email;
    public $terms;
    private static $cache = null;

    public function __construct($vendor)
    {
        $this->id = $vendor['id'];
        $this->vendor_name = $vendor['vendor_name'];
        $this->vendor_code = $vendor['vendor_code'];
        $this->account_number = $vendor['account_number'];
        $this->vendor_address = $vendor['vendor_address'];
        $this->contact_number = $vendor['contact_number'];
        $this->email = $vendor['email'];
        $this->terms = $vendor['terms'];
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

        $stmt = $connection->prepare('DELETE FROM `vendors` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('SELECT * FROM `vendors`');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($item) { return new Vendor($item); }, $result);

        return static::$cache;
    }

    public static function add($vendor_name, $vendor_code, $account_number, $vendor_address, $contact_number, $email, $terms)
    {
        global $connection;

        // Check if the vendor name already exists
        if (static::findByName($vendor_name)) {
            throw new Exception('Vendor name already exists');
        }

        // Prepare the SQL query
        $stmt = $connection->prepare('INSERT INTO `vendors` (vendor_name, vendor_code, account_number, vendor_address, contact_number, email, terms) VALUES (:vendor_name, :vendor_code, :account_number, :vendor_address, :contact_number, :email, :terms)');

        // Bind parameters
        $stmt->bindParam(":vendor_name", $vendor_name);
        $stmt->bindParam(":vendor_code", $vendor_code);
        $stmt->bindParam(":account_number", $account_number);
        $stmt->bindParam(":vendor_address", $vendor_address);
        $stmt->bindParam(":contact_number", $contact_number);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":terms", $terms);

        // Execute the query
        $stmt->execute();
    }

    public static function findByName($name)
    {
        global $connection;
    
        $stmt = $connection->prepare("SELECT * FROM `vendors` WHERE vendor_name=:name");
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
        $result = $stmt->fetchAll();
    
        if (!empty($result)) {
            return new Vendor($result[0]);
        }
    
        return null;
    }

    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `vendors` WHERE id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new Vendor($result[0]);
        }

        return null;
    }
}