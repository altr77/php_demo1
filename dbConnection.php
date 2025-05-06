<?php
$con = new mysqli('ec2-13-201-188-250.ap-south-1.compute.amazonaws.com', 'admin', 'StrongPassword123', 'oesm');
//all the variables defined here are accessible in all the files that include this one
$con= new mysqli('ec2-13-201-188-250.ap-south-1.compute.amazonaws.com','admin','','oesm')or die("Could not connect to mysql".mysqli_error($con));
?>
