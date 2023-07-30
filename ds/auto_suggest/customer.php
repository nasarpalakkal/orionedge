<?php
    //database configuration
    include('../../dbConfig.php');
    
    //get search term
    $searchTerm = $_GET['term'];
    
    //get matched data from skills table    
    $data = [];
$query = $db->query("SELECT * FROM customer_details  WHERE customer_name LIKE '%".$searchTerm."%' or customer_contact1 like '%".$searchTerm."%' or code like '%".$searchTerm."%'  ORDER BY customer_name ASC");
    while ($row = $query->fetch_assoc()) {
        $data[] =$row['id'].'--'.$row['code'].'--'.$row['customer_name'].'--'.$row['CustomerType'];
    }
    
    //return json data
    echo json_encode($data);
?>