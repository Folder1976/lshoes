<!-- Sergey Kotlyarov 2016 folder.list@gmail.com -->
<?php
$file = explode('/', __FILE__);
if(strpos($_SERVER['PHP_SELF'], $file[count($file)-1]) !== false){
	header("Content-Type: text/html; charset=UTF-8");
	die('Прямой запуск запрещен!');
}

$key = 'operation_id';

include "class/operation.class.php";
$Operation = new Operation();

//include "class/users.class.php";
$Users = new Users();

$UsersList = $Users->getUsers();
$TypeList = $Operation->getTypes();

$filter_data = array();

$List = $Operation->getOperations($filter_data);

include "class/warehouse.class.php";
$Warehouse = new Warehouse();
$warehouses = $Warehouse->getWarehouses();

$warehouses[0]['name'] = 'Приход';
$warehouses[-1]['name'] = 'Продажа';

include "class/customer.class.php";
$Customer = new Customer();
$postavs = $Customer->getCustomers(4);

?>
<br>
<h1>Справочник : Операций</h1>
<div style="width: 90%">
<div class="table_body">

<table class="text">
    <tr>
        <th>Номер документа</th>
        <th>Дата создания</th>
        <th>Тип</th>
        <th>Примечение</th>
        <th>Сумма</th>
        <th>От куда </th>
        <th>Куда</th>
        <th>Поставщик</th>
        <th>Пользователь</th>
        <th>Дата изменения</th>
    </tr>
	<form method="GET">
		<tr>
			<th><input type="text" class="operation_sort" name="operation_id"
					value="<?php echo isset($_GET['operation_id']) ? $_GET['operation_id'] : '' ;?>"></th>
			<th><input type="text" class="operation_sort datepicker" name="date"
					value="<?php echo isset($_GET['date']) ? $_GET['date'] : '' ;?>"></th>
			<th>
				<select class="header_edit" id="type_id" style="width:100%;">
					<option value="0">* * *</option>
					<?php foreach($TypeList as $index => $value){?>
						<?php if(isset($_GET['type_id']) AND $index == (int)$_GET['type_id']){ ?>
							<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
						<?php }else{ ?>
							<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select></th>
			<th><input type="text" class="operation_sort" name="comment"
					value="<?php echo isset($_GET['comment']) ? $_GET['comment'] : '' ;?>"></th>
			<th></th>
			<th>
				<select class="header_edit" id="from_warehouse_id" style="width:100%;">
					<option value="0">* * *</option>
					<?php foreach($warehouses as $index => $value){?>
						<?php if(isset($_GET['from_warehouse_id']) AND $index == (int)$_GET['from_warehouse_id']){ ?>
							<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
						<?php }else{ ?>
							<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</th>
			<th>
				<select class="header_edit" id="to_warehouse_id" style="width:100%;">
					<option value="0">* * *</option>
					<?php foreach($warehouses as $index => $value){?>
						<?php if(isset($_GET['to_warehouse_id']) AND $index == (int)$_GET['to_warehouse_id']){ ?>
							<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
						<?php }else{ ?>
							<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</th>
			<th>
				<select class="header_edit" id="customer_id" style="width:100%;">
					<option value="0">* * *</option>
					<?php foreach($postavs as $index => $value){?>
						<?php if(isset($_GET['customer_id']) AND $index == (int)$_GET['customer_id']){ ?>
							<option value="<?php echo $index; ?>" selected><?php echo $value['name']; ?></option>
						<?php }else{ ?>
							<option value="<?php echo $index; ?>"><?php echo $value['name']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</th>
			<th>
				<select class="header_edit" id="user_id" style="width:100%;">
					<option value="0">* * *</option>
					<?php foreach($UsersList as $index => $value){?>
						<?php if(isset($_GET['user_id']) AND $index == (int)$_GET['user_id']){ ?>
							<option value="<?php echo $index; ?>" selected><?php echo $value['lastname'].' '.$value['firstname']; ?></option>
						<?php }else{ ?>
							<option value="<?php echo $index; ?>"><?php echo $value['lastname'].' '.$value['firstname']; ?></option>
						<?php } ?>
					<?php } ?>
				</select>
			</th>
			<th><input type="text" class="operation_sort datepicker" name="edit_date"
					value="<?php echo isset($_GET['edit_date']) ? $_GET['edit_date'] : '' ;?>"></th>
		</tr>
	</form>

<?php foreach($List as $index => $ex){ ?>
  
	<tr class="link_table_row" id="<?php echo $ex[$key];?>" data-type_id="<?php echo $ex['type_id']; ?>">
        <td class="mixed"><?php echo $ex[$key];?></td>
        <td class="mixed"><?php echo date('d-m-Y H:i:s',strtotime($ex['date'])); ?></td>
        <td class="left" style="font-weight: bold; background-color: <?php echo $TypeList[$ex['type_id']]['color']; ?>;"><?php echo $TypeList[$ex['type_id']]['name']; ?></td>
        <td class="left"><?php echo $ex['comment']; ?></td>
        <td class="right"><?php echo number_format($ex['summ'], 2, '.', ''); ?></td>
		<td class="right"><?php echo $warehouses[$ex['from_warehouse_id']]['name']; ?></td>
        <td class="left"><?php echo $warehouses[$ex['to_warehouse_id']]['name']; ?></td>
        <td class="mixed"><?php echo isset($postavs[$ex['customer_id']]) ? $postavs[$ex['customer_id']]['name'] : ''; ?></td>
        <td class="mixed"><?php echo $UsersList[$ex['user_id']]['lastname'] . ' ' . $UsersList[$ex['user_id']]['firstname']; ?></td>
        <td class="mixed"><?php echo date('d-m-Y H:i:s',strtotime($ex['edit_date'])); ?></td>
    </tr>
	
<?php } ?>

</table>

</div>

</div>

<!--script type="text/javascript" src="/backend/js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="/backend/js/ui/jquery.ui.datepicker-ru.js"></script-->

<!--script type="text/javascript" src="/backend/js/jquery/jquery-1.8.2.min.js"></script-->
<script type="text/javascript" src="/backend/js/jquery/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="/backend/js/jquery/ui/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="/backend/js/jquery/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="/backend/js/jquery/ui/jquery.ui.datepicker-ru.js"></script>
<script>
	$(document).on('click', '.link_table_row', function(){
		
		var type_id = $(this).data('type_id');
		var operation_id = $(this).attr('id');
		
		if(type_id == 1){
			
			window.open('/backend/index.php?route=operation_prihod/prihod.index.php&operation_id='+operation_id,'_blank');
		}else if(type_id == 3){
			
			window.open('/backend/index.php?route=operation_shop/shop.index.php&operation_id='+operation_id,'_blank');
		}
		
	});
	
	$(document).ready(function(){
		$(".datepicker").datepicker();
	});

</script>