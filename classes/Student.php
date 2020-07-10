<?php

class Student extends StudentAbstract
{
    
    /**
     * @param array $data
     * @return void
     */
    public function determineAction(array $data) : void
    {
      $this->action = $this->getValue($data[0]);
    
      switch ($this->action) {

        case 'add': 
               $this->getInput();
               $errors = $this->validateInputs();
               if (count($errors)>0){
                    $this->printErrors($errors);
               } else {
                $this->addStudent();
               }
               
            break;
        case 'edit':
              $this->id = $this->getValue($data[1]);

              if ($this->studentExists()){
                  $this->getStudentData();
                  $this->getInput();
                  $this->changeStudentData();
                  $file_name = $this->filePath();
                  
                  file_put_contents($file_name, $this->prepareData());
                  echo 'Success fully updated';

                 
              } else {
                  $errors[] = 'Sorry, student id ' . $this->id . ' does not exists';
                  $this->printErrors($errors);
              }
              
            break;
        case 'delete':
            $this->id = $this->getValue($data[1]);   
            if ($this->studentExists()){
                $this->deleteStudent();
                echo 'Student successfully deleted.' . PHP_EOL;
            } else {
                $errors[] = 'Sorry, student id ' . $this->id . ' does not exists';
                $this->printErrors($errors);
            }
            break;
        case 'search':
            echo 'Enter search criteria: ';
            $criteria = trim(fgets(STDIN,1024));
            $this->searchStudent($criteria);

        break;            
      }
    }
    
    /**
     * @param
     * @return void
     */
    protected function getInput()
    {
        if (empty($this->id)) {
            echo 'Enter id:';
            $this->id = trim(fgets(STDIN,1024));
        }
        
        echo 'Enter name:';
        $this->name = trim(fgets(STDIN,1024));
        echo 'Enter surname:';
        $this->surname = trim(fgets(STDIN,1024));
        echo 'Enter age:';
        $this->age = trim(fgets(STDIN,1024));
        echo 'Enter curriculum:';
        $this->curriculum = trim(fgets(STDIN,1024));
    }
    /**
     * @param 
     * @return void
     */
    protected function changeStudentData() : void
    {
        $this->id = $this->studentData['id'];
        $this->name = !empty($this->name) ? $this->name : $this->studentData['name'];
        $this->surname = !empty($this->surname) ? $this->surname : $this->studentData['surname'];
        $this->age = !empty($this->age) ? $this->age : $this->studentData['age'];
        $this->curriculum = !empty($this->curriculum) ? $this->curriculum : $this->studentData['curriculum'];
    }
    /**
     * @param 
     * @return void
     */
    protected function prepareData()
    {
        return json_encode([
            'id' =>  $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'age' => $this->age,
            'curriculum' => $this->curriculum
        ]);   
    }
   
    /**
     * @param 
     * @return array
     */
    private function validateInputs() : array
    {
        $errors = array();

        if($this->studentExists()) {
            $errors[] = 'Student Already Exists';
        }
       
        if (empty($this->id) ||!is_numeric($this->id) || strlen($this->id) != 7) {
            $errors[] = 'student id is required must be numeric and length of 7';
        }

        if (empty($this->name) || !is_string($this->name)) {
            $errors[] = 'name is required and must be a string';
        }
        if (empty($this->surname) && !is_string($this->surname)) {
            $errors[] = 'surname is required and must be a string';
        }
        if (empty($this->age) && !is_numeric($this->age)) {
            $errors[] = 'age is required and must be numeric';
        }
        if (empty($this->curriculum) || !is_string($this->curriculum)) {
            $errors[] = 'curriculum is required and must be a string';
        }

        return $errors;

    }
    /**
     * @param 
     * @return bool
     */
    protected function studentExists(): bool 
    {
           
      if (file_exists($this->filePath())) {
          return true;
      } 
      return false;
    }
    protected function printErrors(array $errors) : void 
    {
        echo PHP_EOL .'Please find the following errors:' . PHP_EOL;

        foreach($errors as $error) {
            echo $error . PHP_EOL;
        }
    }

    /**
     * @param 
     * @return void
     */
    private function getStudentData() 
    {      
        $data = file_get_contents($this->filePath());

        $this->studentData = json_decode($data, true);
    }
    /**
     * @param
     * @return void
     */

    protected function deleteStudent() : void
    {
        unlink($this->filePath());
        $directories =  preg_grep('/^([^.])/', scandir($this->file_path. substr($this->id,0,2)));
        if (count($directories) == 0) {
            rmdir($this->file_path. substr($this->id,0,2));
        }
    }
    /**
     * @param string $criteria
     * @return void
     */
    protected function searchStudent(string $criteria) : void
    {
       //exclude dot 
       $directories =  preg_grep('/^([^.])/', scandir($this->file_path));
        $criteria_empty = true;
       if (!empty($criteria)) {
        $criteria_empty = false;
        $data = explode('=', $criteria);
      
       }

       echo PHP_EOL . "|id\t\t |Name \t |Surname \t|Age \t|Curriculum" .PHP_EOL;
       foreach($directories as $directory) {
           $files = preg_grep('/^([^.])/', scandir($this->file_path . $directory));
            
           if ($criteria_empty) {
            foreach ($files as $file) {
                $student = file_get_contents($this->file_path .'/'. $directory .'/' .   $file);
                $student =  json_decode($student, true);
                $this->displayData($student);
            }
           } else {
            foreach ($files as $file) {
                
                $student = file_get_contents($this->file_path .'/'. $directory .'/' .   $file);
                $student =  json_decode($student, true);
                
                if (array_key_exists($data[0],$student)) {
                    $found_key= array_search($data[1], $student);
                   
                    if($found_key){
                        $this->displayData($student);
                    }
                    
                }
                
            } 
           }
       }
    }
    
}



?>