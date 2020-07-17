<?php 

require_once('classes/StudentInterface.php');
require_once('classes/StudentAbstract.php');
require_once('classes/Student.php');
//Remove file name
unset($argv[0]);
//Reset array index
$arguments = array_merge($argv);
if (!empty($argv)) {   

     //Instatiate student object
     $student = new Student();    
     $student->determineAction($arguments);
   
}

?>