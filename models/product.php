<?php

class Product {

    private $conn;
    private $table = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
				FROM
                " . $this->table . " p 
				LEFT JOIN categories c ON p.category_id = c.id
				ORDER BY p.created DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;

    }

    function create(){

        // query to insert record
        $query = "INSERT INTO " . $this->table . "
			SET
				name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }

    public function readOne(){

        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM ".$this->table." p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description = $row['description'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];

    }

    public function update(){

        $query = "UPDATE ".$this->table. "
        SET name=:name,
        price=:price,
        description=:description,
        category_id=:category_id
        WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        //sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //bind new values
        $stmt->bindParam(':name',$this->name);
        $stmt->bindParam(':price',$this->price);
        $stmt->bindParam(':description',$this->description);
        $stmt->bindParam(':category_id',$this->category_id);
        $stmt->bindParam(':id',$this->id);

        if($stmt->execute()){

            return true;

        }
        else{

            return false;

        }

    }

    public function delete(){

        $query = "DELETE FROM ".$this->table." WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1,$this->id);

        if($stmt->execute()){

            return true;

        }
        else{

            return false;

        }

    }
    
    public function search($key){

        $query = "SELECT c.name as category_name, p.id, p.name, 
            p.description, p.price, p.category_id, p.created
            FROM ".$this->table." p
            LEFT JOIN categories c ON c.id = p.category_id 
            WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
            ORDER BY p.created DESC";

        $stmt = $this->conn->prepare($query);

        $key = htmlspecialchars(strip_tags($key));
        $key = "%{$key}%";

        $stmt->bindParam(1,$key);
        $stmt->bindParam(2,$key);
        $stmt->bindParam(3,$key);

        $stmt->execute();

        return $stmt;

    }

    public function readPaging($from_record_num, $records_per_page){

        $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM " . $this->table . " p 
            LEFT JOIN categories c ON c.id = p.category_id
            ORDER BY p.created DESC
            LIMIT ?,?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1,$from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2,$records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;

    }

    public function count(){

        $query = "SELECT COUNT(*) as total_rows from ".$this->table." ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["total_rows"];

    }


}
