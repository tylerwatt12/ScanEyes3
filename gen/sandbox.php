<?php
include 'libraries/db-read.php';
include 'libraries/db-write.php';
include 'libraries/gen-gen.php';
#$uname = charNumOnly($_GET['username']);  //CHANGE TO POST WHEN FINISHED
#$pwd = $_GET['password']; //CHANGE TO POST WHEN FINISHED
#$loginReturn = queryUsernameGetUser($uname);
#echo "Password from GET: ".$pwd."<br>";
#echo "PWSalt from DB: ".$loginReturn['PWSALT']."<br>";
#echo "Computed PWD: ".md5($pwd.$loginReturn['PWSALT'])."<br>";
#echo "PWSalt from DB: ".$loginReturn['PWD']."<br>";


#if ($loginReturn['PWD'] == md5($pwd.$loginReturn['PWSALT'])) {
# 	echo "HASH MATCH";
# } ;
#var_dump (addUser("gatesb","billgates","billgates@microsoft.com","Gates","Bill"));
#var_dump (verifyUser("rossb","kXxAUJCGiROuYqkQS2S6wLI6Qewbjwwb"));
 //$salt = saltGen(32);
 //echo "<br>".$salt."<br>";
 //echo md5("password".$salt);


//password rate limit 

 //set session var
 //If ($loginReturn['ACCTENABLED'] == 1){AUTH['isloggedin'] = 1}
 //AUTH['loglvl'] = 
 //auth['username'] = $loginReturn['USERNAME']
 //AUTH['fname'] = $loginReturn['FN']
 //AUTH['Lname'] = $loginReturn['LN']
echo sendAuthEmail("tylerwatt12@gmail.com","test code");
?>
