
<?php


include_once '../connect.php';


$column = array(
    'id', 'code', 'code category', 'student name', 'student phone', 'price','status'
);

$query = "SELECT * FROM codes ";

if (isset($_POST['search']['value'])) {
    $query .= '
 WHERE code LIKE "%' . $_POST['search']['value'] . '%" 
 OR studentid LIKE "%' . $_POST['search']['value'] . '%" 
 OR price LIKE "%' . $_POST['search']['value'] . '%" 
 ';
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY code ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $con->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $con->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

foreach ($result as $row) {
  
    $statement=$con->prepare("select name from codes_category ");
    $statement->execute();
    $category_name=$statement->fetch();
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['code'];
    $sub_array[] = $category_name['name'];
    if($row['studentid']!=NULL){
        $statement=$con->prepare("select * from students where id=?");
        $statement->execute(array($row['studentid']));
        $student=$statement->fetch();
        $sub_array[]= $student['name'];
        $sub_array[]= $student['phone'];
    }
    else{
        $sub_array[]= ' ';
        $sub_array[]= ' ';
    }
        
    $sub_array[] = $row['price'];
   
  if($row['studentid'] != NULL){
      $sub_array[]=' <button type="button" class="btn btn-success btn-lg btn-block mb-2">Used</button>'.'
    <a href="codes_show.php?clear&id='.$row['id'].'">
    <button type="button" class="btn btn-success btn-lg btn-block">Clear student</button>
    </a>'.
    '<a href="codes_show.php?delete&id='.$row['id'].'"><button type="button" class="btn btn-danger btn-lg btn-block">Delete</button></a>';
    
  }else{
      $sub_array[]= '<button type="button" class="btn btn-warning btn-lg btn-block">Pending</button>'
      .'<a href="codes_show.php?delete&id='.$row['id'].'"><button type="button" class="btn btn-danger btn-lg btn-block">Delete</button></a>';
  }



    
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "SELECT * FROM codes";
    $statement = $con->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw'    => intval($_POST['draw']),
    'recordsTotal'  => count_all_data($con),
    'recordsFiltered' => $number_filter_row,
    'data'    => $data
);

echo json_encode($output);
