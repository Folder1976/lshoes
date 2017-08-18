<?php

class Operation
{
	private $db;
	private $pp;
	private $session;
	
    function __construct (){
		
		if(!isset($_SESSION['default'])) session_start();
		
		$this->session = $_SESSION;
		
		$this->pp = DB_PREFIX;
		
		//Новое соединение с базой
		$this->db = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or die("Error db connection "); 
		mysqli_set_charset($this->db,"utf8");
		
	}
	
    public function getOperations($data){
		$pp = $this->pp;
		
		$sql = 'SELECT * FROM `'.$this->pp.'operation` ORDER BY operation_id DESC;';
		//echo $sql;
		$r = $this->db->query($sql);
		
		$data = array();
		if($r->num_rows > 0){
			while($row = $r->fetch_assoc()){
				$data[$row['operation_id']] = $row;
			}
		}
	
		return $data;
		
	}
	
    public function getLastZakup($product_id){
		$pp = $this->pp;
		
		$sql = 'SELECT zakup FROM `'.$this->pp.'operation_product`
					WHERE product_id="'.(int)$product_id.'" AND type_id="1"
					ORDER BY operation_id DESC LIMIT 1;';
		//echo $sql;
		$r = $this->db->query($sql);
		
		if($r->num_rows > 0){
			$row = $r->fetch_assoc();
			return (int)$row['zakup'];
			
		}else{
			
			$sql = 'SELECT zakup FROM '.$this->pp.'product WHERE product_id="'.(int)$product_id.'" LIMIT 1';
			$r = $this->db->query($sql);
		
			if($r->num_rows > 0){
				
				$row = $r->fetch_assoc();
				return (int)$row['zakup'];
				
			}
		}
	
		return 0;
		
	}
	
    public function getLastPrice($product_id){
		$pp = $this->pp;
		
		$sql = 'SELECT price_invert FROM `'.$this->pp.'operation_product`
					WHERE product_id="'.(int)$product_id.'"
					ORDER BY operation_id DESC LIMIT 1;';
		//echo $sql;
		$r = $this->db->query($sql);
		
		if($r->num_rows > 0){
			
			$row = $r->fetch_assoc();
			return (int)$row['price_invert'];
			
		}else{
			
			$sql = 'SELECT price FROM '.$this->pp.'product WHERE product_id="'.(int)$product_id.'" LIMIT 1';
			$r = $this->db->query($sql);
		
			if($r->num_rows > 0){
				
				$row = $r->fetch_assoc();
				return (int)$row['price'];
				
			}	
		}
	
		return 0;
		
	}
	
	/* $data['operation_id']
	 * $data['product_id']
	 * $data['zakup']
	 */
	public function dellProductRow($data){
		
		$sql = 'DELETE FROM ' . DB_PREFIX . 'operation_product
				WHERE
				operation_id = "'.$data['operation_id'].'" AND
				product_id = "'.$data['product_id'].'" AND
				zakup = "'.$data['zakup'].'" 
				';
		//echo $sql;
		$this->db->query($sql) or die('saddf'.$sql);
		
	}
	
	/* $data['operation_id']
	 * $data['product_id']
	 * $data['zakup']
	 * $data['quantity']
	 * $data['size_id']
	 */
	public function updateProductQuantity($data){
		
		if((int)$data['quantity'] > 0){
	
			$sql = 'INSERT INTO ' . DB_PREFIX . 'operation_product
						SET
						quantity = "'.(int)$data['quantity'].'",
						operation_id = "'.$data['operation_id'].'",
						product_id = "'.$data['product_id'].'",
						size_id = "'.$data['size_id'].'",
						zakup = "'.$data['zakup'].'"
						on duplicate key update
						quantity = "'.(int)$data['quantity'].'"
						';
		
		}else{
			
			$sql = 'DELETE FROM ' . DB_PREFIX . 'operation_product
						WHERE
						operation_id = "'.$data['operation_id'].'" AND
						product_id = "'.$data['product_id'].'" AND
						size_id = "'.$data['size_id'].'" AND
						zakup = "'.$data['zakup'].'" 
						';
			
		}
		//echo $sql;
		$this->db->query($sql) or die('sadlkjg345 '.$sql);
		
	}
	
	public function updateOperationSumm($operation_id){
	
		//Пресчет сумм
		$sql = 'UPDATE '.$this->pp.'operation_product
				SET
				summ = (quantity * zakup)
				WHERE
				operation_id = "'.$operation_id.'"';
		$this->db->query($sql);
	
		//Получим сумму
		$sql = 'SELECT SUM(summ) AS summ FROM '.$this->pp.'operation_product
				WHERE
				operation_id = "'.$operation_id.'"';
		$r = $this->db->query($sql);
		$row = $r->fetch_assoc();
		
		//Запишем новую сумму
		$sql = 'UPDATE '.$this->pp.'operation
				SET
				summ = "'.(int)$row['summ'].'"
				WHERE
				operation_id = "'.$operation_id.'"';
		$this->db->query($sql);
	
		return (int)$row['summ'];
	}
	
    public function getOperation($operation_id){
		$pp = $this->pp;
		
		$sql = 'SELECT * FROM `'.$this->pp.'operation` WHERE operation_id = "'.(int)$operation_id.'" LIMIT 1;';
		//echo $sql;
		$r = $this->db->query($sql);
		
		$return = array();
		if($r->num_rows > 0){
			$row = $r->fetch_assoc();
			return $row;
			
		}
	
		return false;
		
	}

	public function getProductMaster($operation_id, $index){
		
		$pp = $this->pp;
		
		list($product_id, $zakup) = explode('_',$index);
		
		$sql = 'SELECT distinct OP.master_id, M.name
					FROM `'.$this->pp.'operation_product` OP
					LEFT JOIN `'.$this->pp.'master` M ON M.master_id = OP.master_id
					
					WHERE operation_id = "'.(int)$operation_id.'"
					AND product_id = "'.(int)$product_id.'"
					AND zakup = "'.$zakup.'"
					ORDER BY M.name;';
		//echo $sql;
		$r = $this->db->query($sql) or die($sql);
		
		$return = '';
		if($r->num_rows > 0){
			
			while($row = $r->fetch_assoc()){
				$return .= $row['name'].', ';
			}
			
			return trim($return, ', ');
		}
	
		return '';
		
		
		
	}
	
    public function getOperationProducts($operation_id){
		$pp = $this->pp;
		
		$sql = 'SELECT *, OP.quantity AS operation_quantity, OP.zakup AS operation_zakup
					FROM `'.$this->pp.'operation_product` OP
					LEFT JOIN `'.$this->pp.'product` P ON P.product_id = OP.product_id
					LEFT JOIN `'.$this->pp.'product_description` PD ON P.product_id = PD.product_id
					LEFT JOIN `'.$this->pp.'size` S ON S.size_id = OP.size_id
					WHERE operation_id = "'.(int)$operation_id.'"
					ORDER BY PD.name;';
		//echo $sql;
		$r = $this->db->query($sql) or die($sql);
		
		$return = array();
		if($r->num_rows > 0){
			while($row = $r->fetch_assoc()){
				$return[] = $row;
			}
			
			return $return;
		}
	
		return false;
		
	}
	
	public function addProduct($data){
		$pp = $this->pp;
		
		$sql = 'INSERT INTO ' . $this->pp . 'operation_product
					SET
					quantity = "'.(int)$data['quantity'].'",
					operation_id = "'.$data['operation_id'].'",
					product_id = "'.(int)$data['product_id'].'",
					type_id = (SELECT type_id FROM '.$this->pp.'operation WHERE operation_id="'.$data['operation_id'].'"),
					size_id = "'.$data['size_id'].'",
					zakup = "'.$data['zakup'].'",
					master_id = "'.$data['master_id'].'",
					currency_id = "'.$data['currency_id'].'",
					price_invert = "'.$data['price_invert'].'"
					on duplicate key update
					quantity = "'.(int)$data['quantity'].'"
					';
					
		//echo $sql;
		$this->db->query($sql) or die($sql);
		
		return $this->db->insert_id;
		
	}
	
	public function addOperation($data){
		$pp = $this->pp;
		
		$sql = 'INSERT INTO ' . $this->pp . 'operation
					SET
					date = "'.date('Y-m-d H:i:s').'",
					edit_date = "'.date('Y-m-d H:i:s').'",
					user_id = "'.$this->session['default']['user_id'].'",
					customer_id = "'.$data['customer_id'].'",
					summ = "0",
					comment = "'.$data['comment'].'",
					type_id = "'.$data['type_id'].'",
					from_warehouse_id = "'.$data['from_warehouse_id'].'",
					to_warehouse_id = "'.$data['to_warehouse_id'].'"
					';
					
		//echo $sql;
		$this->db->query($sql) or die($sql);
		
		return $this->db->insert_id;
		
	}

	public function getTypes(){
		
		$pp = $this->pp;
		
		$sql = 'SELECT * FROM `'.$this->pp.'operation_type` ORDER BY name;';
		//echo $sql;
		$r = $this->db->query($sql);
		
		$data = array();
		if($r->num_rows > 0){
			while($row = $r->fetch_assoc()){
				$data[$row['type_id']] = $row;
			}
		}
	
		return $data;
		
	}

}

?>
