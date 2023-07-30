<?php
    //database configuration
    include('../../dbConfig.php');
    
    //get search term
    $searchTerm = $_GET['term'];
    
    //get matched data from skills table    
    $data = [];
$query = $db->query("SELECT * FROM inventory LEFT JOIN inventory_uom on inventory.item_no=inventory_uom.item_no LEFT JOIN tb_units on inventory_uom.unit=tb_units.id WHERE inventory.item_no LIKE '%".$searchTerm."%' or inventory.item_descr like '%".$searchTerm."%' or inventory.item_descr_ar like '%".$searchTerm."%' or inventory.ProductBarcode like '%".$searchTerm."%' or inventory_uom.barcode like '%".$searchTerm."%' ORDER BY inventory.item_no ASC");
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['item_no'].'--'.$row['item_descr'].'--'.$row['item_descr_ar'].'--'.$row['descr'].'--'.$row['retail_price'];
    }
    
    //return json data
    echo json_encode($data);
?>