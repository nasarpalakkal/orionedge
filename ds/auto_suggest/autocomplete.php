<?php
    //database configuration
    $dbHost = 'localhost';
/*    $dbUsername = 'e1k5v9x0_pos';
    $dbPassword = 'pos@april2018';
    $dbName = 'e1k5v9x0_pos';
*/    
	$dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'pos_retail_shop';
    //connect with the database
    $db = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
    
    //get search term
    $searchTerm = $_POST['term'];
    
    //get matched data from skills table
    $query = $db->query("SELECT * FROM inventory WHERE item_no LIKE '%".$searchTerm."%' or item_descr like '%".$searchTerm."%' or sku_barcode like '%".$searchTerm."%' or upn_barcode like '%".$searchTerm."%' ORDER BY item_no ASC");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['item_no'].'--'.$row['item_descr'];
    }
    
    //return json data
    echo json_encode($data);
?>