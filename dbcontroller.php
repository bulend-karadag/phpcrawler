<?php
//mysqli_report(MYSQLI_REPORT_ALL);
class dataChange {
	private $host = "";
	private $user = "";
	private $password = "";
	private $database = "";
	private $conn;
	
	function __construct() {
		 $this->conn = $this->connectDB();
	}
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	function login($query, $username, $p_submitted) {
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($user_id, $p_existed);
				$stmt->fetch();
				if (password_verify($p_submitted, $p_existed)) { 
					return array(1, $user_id);
				} else {
					return array(2, 0);
				}
			} else {
					return array(3, 0);
			}
			$stmt->close();
	}
	
	function runQuery($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;	
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	function selectIdQuery($query, $id) {
		$stmt = $this->conn->prepare($query);
		//echo $query. "<br>";
		$stmt->bind_param("i", $id);
		if ($stmt->execute()) {
		$result = $this->get_result($stmt);
		$stmt->close();
		return $result;
		}
	}
	
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
	
	function insertQuery($table, array $fields, array $values) {
		$query= "INSERT INTO  {$table} (".implode(",", $fields).") VALUES (".implode(',', array_fill(0, count($fields), '?')).")";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param(...$values);
		if ($stmt->execute()) {$stmt->close(); return true;} else {$stmt->close(); return false;}
	}
	
	function updateQuery($table, array $fields, array $values) {	
		foreach($fields as $field) { $fieldNames []= $field."=?";}
		$query= "UPDATE {$table} SET ". implode(", ", $fieldNames) ." WHERE ".$this->whichId($table)."id = ?";
		//echo $query;
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param( ...$values);		
		if ($stmt->execute()) {$stmt->close(); return true;} else {$stmt->close(); return false;}	
	}
	
	
	function deleteQuery($table, $id) {	
		$query= "DELETE FROM {$table} WHERE ".$this->whichId($table)."id = ?";
		//echo $query;
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param('i',$id);
			
		if ($stmt->execute()) {$stmt->close(); return true;} else {$stmt->close(); return false;}	
	}
	
	function get_result($stmt) {
    	$result = array();
    	$stmt->store_result();
	    	for ( $i = 0; $i < $stmt->num_rows; $i++ ) {
		$Metadata = $stmt->result_metadata();
		$PARAMS = array();
			while ( $Field = $Metadata->fetch_field() ) {
			    $PARAMS[] = &$result[$i][$Field->name];
			}
		call_user_func_array(array($stmt, 'bind_result'), $PARAMS);
		$stmt->fetch();
	    	}
    	return $result[0];
	}
	
	function whichId ($table){
	if ($table == 'registered_users'){return 'user_';}
	else if ($table == 'user_preferences'){return 'user_';}
	else if ($table == 'def_preferences'){return 'p_';}	
	else {return '';}	
	}
}
?>
