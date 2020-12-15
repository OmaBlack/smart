<?php
include 'db.php';

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $hospital_name = $_POST['hospital_name'];
    $position_in_hospital = $_POST['position_in_hospital'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $hospital_location = $_POST['hospital_location'];


    // $sql = "INSERT INTO `contract`(`first_name`,`last_name`, `hospital_name`, `position_in_hospital`, `phone`,`email`, `hospital_location`)
 //    VALUES ('$first_name', '$last_name','$hospital_name','$position_in_hospital','$phone','$email','$hospital_location')";
 //
 //
 //   if ($con->query($sql) === TRUE) {
 //    return true;
 // 	echo "testingo	";
 //   } else {
 //     echo "Error: " . "<br>" . $conn->error;
 //   }
   
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_r( json_encode($_POST) );
	echo 'someting';
 }else{
	 echo 'nothing';
 }
$con->close();
?>