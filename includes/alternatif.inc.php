<?php
class Alternatif{
	
	private $conn;
	private $table_name = "kenshin";
	
	public $id;
	public $kt;
	public $jm;
	
	public function __construct($db){
		$this->conn = $db;
	}
	
	function insert(){
		
		$query = "insert into ".$this->table_name." values('',?,'')";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->kt);
		
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
		
	}
	
	function readAll(){

		$query = "SELECT * FROM ".$this->table_name." ORDER BY id_kenshin ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		
		return $stmt;
	}
	
	// used when filling up the update product form
	function readOne(){
		
		$query = "SELECT * FROM " . $this->table_name . " WHERE id_kenshin=? LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $row['id_kenshin'];
		$this->kt = $row['nama_kenshin'];
	}
	
	// update the product
	function update(){

		$query = "UPDATE 
					" . $this->table_name . " 
				SET 
					nama_kenshin = :kt
				WHERE
					id_kenshin = :id";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(':kt', $this->kt);
		$stmt->bindParam(':id', $this->id);
		
		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	// delete the product
	function delete(){
	
		$query = "DELETE FROM " . $this->table_name . " WHERE id_kenshin = ?";
		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);

		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
}
?>
