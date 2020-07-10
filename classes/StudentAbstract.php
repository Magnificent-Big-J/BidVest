<?php 
abstract class StudentAbstract implements StudentInterface{
    protected $id;
    protected $name;
    protected $surname;
    protected $age;
    protected $curriculum;
    protected $file_path = 'students/';
    protected $action;
    protected $studentData;

     /**
     * @param string $value
     * @return string
     */
    public function getValue(string $value) : string
    {
        $actionArray = explode('=', $value);       
        
        return $actionArray[1];
    }

     /**
     * @param 
     * @return void
     */
    public function AddStudent() : void
    {
        $dirPath = $this->file_path . substr($this->id,0,2);
               if ($this->directoryExists($dirPath)) {
                    //check student file
                    $file_name = $this->filePath();
                    if (!file_exists($file_name)) {
                       if(file_put_contents($file_name, $this->prepareData())) {
                           echo 'Student successfully created' .PHP_EOL;
                       } else {
                           echo 'Student already added'.PHP_EOL;
                       }
                    } else {
                        echo 'Student already added' .PHP_EOL;
                    } 
               } else {
                   
                   $dirPath = $this->file_path . substr($this->id,0,2);
                   mkdir($dirPath , 0755, true);
                   $file_name = $this->filePath();
                   file_put_contents($file_name, $this->prepareData());
                   echo 'Student successfully created'.PHP_EOL;
               }
    }


    /**
     * @param string $path
     * @return bool
     */
    private function directoryExists($path) {
      
        if (file_exists($path)){
            return true;
        } else {
            return false;
        }
    }
    /**
     * @param array  student
     * @return void
     */

    protected function displayData(array $student) :void 
    {
        echo "|" . $student['id']. " \t|" . $student['name']. "\t|" . $student['surname']. "\t|". $student['age']. "\t|" . $student['curriculum'] .PHP_EOL;
    }
/**
     * @param 
     * @return string
     */
    protected function filePath() : string
    {
        return $this->file_path . substr($this->id,0,2) . '/'. $this->id . '.json';
    }
}
?>