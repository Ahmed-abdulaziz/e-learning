
<?php

//fetch.php
include_once '../connect.php';
if (isset($_GET['subjectid'])) {
    $subjectid = $_GET['subjectid'];
}
if (isset($_GET['session'])) {
    $session = $_GET['session'];
}


$column = array(
     'name', 'time' ,'numofquestions','minimumdegree','controls'
);

$query = "SELECT * FROM exams WHERE subjectid=$subjectid";

if (isset($_POST['search']['value'])) {
  
    $query .= '
 AND (name LIKE "%' . $_POST['search']['value'] . '%" 
 OR min_degree LIKE "%' . $_POST['search']['value'] . '%" 
 OR numofquestion LIKE "%' . $_POST['search']['value'] . '%" 
)';
 
}
// // echo $query;die;
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY name ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

// $query2 = "SELECT * FROM students where eductionallevel=?";

$statement = $con->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();
// print_r($number_filter_row);

$statement = $con->prepare($query . $query1);

$statement->execute();


$result = $statement->fetchAll();

// print_r($result);

$data = array();
foreach($result as $row){

    if(isset($session)){
        
    $sub_array = array();
    $sub_array[] = $row['name'];
    $sub_array[] = $row['timer'];
    $sub_array[] = $row['numofquestion'];
    $sub_array[] = $row['min_degree'];
if($row['parentexam']!=NULL) { 
    $sub_array[]='<a href="stuexamfail.php?id='.$row['id'].'" class="fw-btn-fill btn-gradient-yellow">Student Fail</a>
';}
$sub_array[]='<a href="edit_sort_exam.php?id='.$row['id'].'" class="fw-btn-fill btn-gradient-yellow"> Sorting Exam</a>
   
    <a href="exams.php?do=dellete&id='.$row['id'].'"  class="fw-btn-fill btn-gradient-yellow">Delete</a>
    
    <a href="editexam.php?do=edit&id='.$row['id'].'"  class="fw-btn-fill btn-gradient-yellow" >Edit</a>
    
    <a href="solvedexam.php?id='.$row['id'].'" class="fw-btn-fill btn-gradient-yellow">Correct</a>
                                            
';


    $data[] = $sub_array;
}
}
function count_all_data($con)
{
   
    global $subjectid;
   
    $query = "SELECT * FROM exams where subjectid=$subjectid";
    $statement = $con->prepare($query);
    $statement->execute();
    // $result2=$statement->fetchAll();
    // print_r($result2);die;
    return $statement->rowCount();
}

$output = array(
    'draw'    => intval($_POST['draw']),
    'recordsTotal'  => count_all_data($con),
    'recordsFiltered' => $number_filter_row,
    'data'    => $data
);

echo json_encode($output);
