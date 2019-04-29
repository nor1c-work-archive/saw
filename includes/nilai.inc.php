<?php
class Nilai{
	
	private $conn;
	private $table_name = "nilai_kenshin";
	
	public $id;
	public $kt;
	public $jm;
	
	public function __construct($db){
		$this->conn = $db;
	}
	
	function insert(){
		
		$query = "insert into ".$this->table_name." values('',?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->kt);
		$stmt->bindParam(2, $this->jm);
		
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
	
	// jika variable tambahan dirubah, menyesuaikan
	function readOne(){
		
		$query = "SELECT * FROM " . $this->table_name . " WHERE id_kenshin=? LIMIT 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$this->id = $row['id_kenshin'];
		$this->kt = $row['id_kriteria'];
		$this->jm = $row['nilai'];
	}
	
	// update nilai
	function update(){

		$query = "UPDATE 
					" . $this->table_name . " 
				SET 
					id_kriteria = :kt,  
					nilai = :jm
				WHERE
					id_kenshin = :id";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(':kt', $this->kt);
		$stmt->bindParam(':jm', $this->jm);
		$stmt->bindParam(':id', $this->id);
		
		// eksekusi query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}
	
	// hapus variable nilai
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
