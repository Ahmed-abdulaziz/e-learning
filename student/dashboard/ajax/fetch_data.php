
<?php

//fetch.php

include_once '../connect.php';
if (isset($_GET['id'])) {
    $level = $_GET['id'];
}
if (isset($_POST['type'])) {
    $type = $_POST['type'];
}
if (isset($_POST['edu'])) {
    $edu = $_POST['edu'];
}
// echo $edu;

$column = array(
    'id', 'username', 'name', 'email', 'phone', 'password', 'phone2', 'governorate', 'school', 'area', 'wallet', 'status', 'controls'
);
// echo $type;die;
if ($type == 'super admin' || $type == 'assistant'  && $edu == 0) {
    // echo "dd";die;
    $query = " SELECT * FROM students  ";

    if (isset($_POST['search']['value'])) {

        $query .= '
 WHERE username LIKE "%' . $_POST['search']['value'] . '%" 
 OR email LIKE "%' . $_POST['search']['value'] . '%" 
 OR name LIKE "%' . $_POST['search']['value'] . '%" 
 OR phone LIKE "%' . $_POST['search']['value'] . '%" 
 OR governorate LIKE "%' . $_POST['search']['value'] . '%" 
 OR area LIKE "%' . $_POST['search']['value'] . '%" 
 OR phone2 LIKE "%' . $_POST['search']['value'] . '%" 
 OR id LIKE "%' . $_POST['search']['value'] . '%" 
 OR school LIKE "%' . $_POST['search']['value'] . '%" 
 OR password LIKE "%' . $_POST['search']['value'] . '%" 
';
    }
} else {
    // echo "dd";die;
    $query = " SELECT * FROM students WHERE eductionallevel = $edu ";

    if (isset($_POST['search']['value'])) {

        $query .= '
 AND (username LIKE "%' . $_POST['search']['value'] . '%" 
 OR email LIKE "%' . $_POST['search']['value'] . '%" 
 OR name LIKE "%' . $_POST['search']['value'] . '%" 
 OR phone LIKE "%' . $_POST['search']['value'] . '%" 
 OR governorate LIKE "%' . $_POST['search']['value'] . '%" 
 OR area LIKE "%' . $_POST['search']['value'] . '%" 
 OR phone2 LIKE "%' . $_POST['search']['value'] . '%" 
 OR id LIKE "%' . $_POST['search']['value'] . '%" 
 OR school LIKE "%' . $_POST['search']['value'] . '%" 
 OR password LIKE "%' . $_POST['search']['value'] . '%" 

 )';
    }
}
// print_r ($query);die;
if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY email  ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
// $query2 = "SELECT * FROM students where eductionallevel=?";
$statement = $con->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$statement = $con->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();
// print_r($result);die;

$data = array();

foreach ($result as $row) {
    $stmt = $con->prepare("SELECT name FROM educationallevels WHERE id = ?");
    $stmt->execute(array($row['eductionallevel']));
    $edulevel = $stmt->fetch();


    $stmt = $con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
    $stmt->execute(array($row['id']));
    $total = $stmt->fetch();

    $stmt = $con->prepare("SELECT sum(money) as totalwallet FROM student_wallet WHERE studentid = ?");
    $stmt->execute(array($row['id']));
    $total = $stmt->fetch();
    // echo $total['totalwallet'] != NULL ? $total['totalwallet'].' EGP' : 0 .' EGP';

    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['username'];
    $sub_array[] = $row['name'];
    $sub_array[] = $row['email'];
    $sub_array[] = $row['phone'];
    $sub_array[] = $row['password'];
    $sub_array[] = $row['phone2'];
    $sub_array[] = $edulevel['name'];
    $sub_array[] = $row['governorate'];
    $sub_array[] = $row['school'];
    $sub_array[] = $row['area'];
    if ($type == "super admin") {
        $sub_array[] = $total['totalwallet'] != NULL ? $total['totalwallet'] . ' EGP' : 0 . ' EGP';
    }

    if ($row['approve'] == 0) {
        $sub_array[] = '<button type="button" class="btn btn-warning btn-lg btn-block">Pending</button>';
    } elseif ($row['approve'] == 1) {
        $sub_array[] = '<button type="button" class="btn btn-success btn-lg btn-block">Apprved</button>';
    } elseif ($row['approve'] == 2) {
        $sub_array[] = '<button type="button" class="btn btn-danger btn-lg btn-block">Disapproved</button>';
    }
    if ($type == 'super admin') {
        $sub_array[] = '<div class="d-flex">
   
       <form method="post" style="display: flex;">
         <input type="hidden" value="' . $row['id'] . '" name="studentid" />
         <input type="hidden" value="' . $level . '" name="level" />
                                                
        <input type="hidden" value="add" name="type" />
        <input type="number" class="form-control mr-1 h-100 " placeholder="Enter Balance" step="any" min="0" required name="balance" />
        <button type="submit" class="fw-btn-fill btn-gradient-yellow mr-1" style="width: 207px;" name="charge">Charge Wallet</button>
     </form>
        
     <form method="post" style="display: flex;">
     <input type="hidden" value="' . $row['id'] . '" name="studentid" >
      <input type="hidden" value="' . $level . '" name="level" >
     <input type="hidden" value="Withdraw" name="type" >
     <input type="number" class="form-control mr-1 h-100" placeholder="Enter Balance" step="any" min="0" required name="balance" >
     <button type="submit" class="fw-btn-fill btn-gradient-yellow mr-1" style="width: 250px;" name="charge">Withdraw Wallet</button>
    </form>
        <a href="allstudents2.php?do=approve&id=' . $row['id'] . '&level=' . $level . '"  class="fw-btn-fill btn-gradient-yellow mr-1">Approve</a>

        <a href="allstudents2.php?do=disapprove&id=' . $row['id'] . '&level=' . $level . '"  class="fw-btn-fill btn-gradient-yellow mr-1">Disapprove </a>
        <a href="stdprofile.php?id=' . $row['id'] . '" target="_blank"  class="fw-btn-fill btn-gradient-yellow mr-1">Profile</a>                    
        <a href="studentedit.php?do=ediit&id=' . $row['id'] . '" target="_blank"  class="fw-btn-fill btn-gradient-yellow mr-1">Edit</a>

        <a href="allstudents2.php?do=delete&id=' . $row['id'] . '&level=' . $level . '"  class="fw-btn-fill btn-gradient-yellow confirmation mr-1">Delete</a>
        </div>       
';
    } elseif ($type == 'teacher' || $type == 'assistant') {
        $sub_array[] =
            '<div class="d-flex">
        <a href="stdprofile.php?id=' . $row['id'] . '" target="_blank"  class="fw-btn-fill btn-gradient-yellow mr-1">Profile</a>                    </div>

';
    } elseif ($type == 'admin' || $type == 'assistant') {
        $sub_array[] = ' <div class="d-flex">

        <a href="allstudents2.php?do=approve&id=' . $row['id'] . '&level=' . $level . '"  class="fw-btn-fill btn-gradient-yellow mr-1">Approve</a>
       
        <a href="allstudents2.php?do=disapprove&id=' . $row['id'] . '&level=' . $level . '"  class="fw-btn-fill btn-gradient-yellow mr-1">Disapprove </a>
        <a href="stdprofile.php?id=' . $row['id'] . '" target="_blank"  class="fw-btn-fill btn-gradient-yellow mr-1">Profile</a>                    
       
        </div>
 
       
       
       ';
    }

    $data[] = $sub_array;
}


function count_all_data($con)
{
    global $type;
    global $edu;
    if ($type == 'super admin' || $type == 'assistant' ) {
        $query = "SELECT * FROM students ";
        $statement = $con->prepare($query);
        $statement->execute();
        // $result2=$statement->fetchAll();
        // print_r($result2);die;
        return $statement->rowCount();
    } else {

        $query = "SELECT * FROM students where eductionallevel=$edu";
        $statement = $con->prepare($query);
        $statement->execute();
        // $result2=$statement->fetchAll();
        // print_r($result2);die;
        return $statement->rowCount();
    }
}

$output = array(
    'draw'    => intval($_POST['draw']),
    'recordsTotal'  => count_all_data($con),
    'recordsFiltered' => $number_filter_row,
    'data'    => $data
);

echo json_encode($output);
