<?php
    //A class to control sql interactability
    class DB
    {
        public $table;
        public $pdo;
        
        //Constructor
        public function __construct()
        {
            $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','Jonathan', 'Hatter12');
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        public function create($params)
        {
            $argu = "INSERT INTO `".$table."`( ";
            
            //Adds the fields the parameters will correspond to
            foreach($params as $key => $value)
            {
               $argu = $argu.htmlentities($key).",";
            }
            unset($key);
            unset($value);
            //Removes the last comma
            $argu = substr($argu, 0, -1)." ) VALUES ( ";
            
            //Adds the parameters themselves
            $arr = array();
            foreach($params as $key => $value)
            {
                $argu = $argu.":".htmlentities($value).",";
                
                $arr[":".$value] = $value;
            }
            unset($key);
            unset($value);
            //Removes the last comma
            $argu = substr($argu, 0, -1)." )";
            
            //Sends the information to the server
            $stmt = $this->pdo->prepare($argu);
            $stmt->execute($arr);
        }
        public function read($other_tables, $params)
        {
            $argu = "'SELECT * FROM ".$this->table;
            
            //Gets the information from the other tables as well
            foreach($other_tables as $key)
            {
                $argu = $argu.",".$key;
            }
            unset($key);
            unset($value);
            //Merges the tables that are pulled
            $argu = $argu." WHERE ";
                
            //If there are other tables, they're merged
            if(!empty($other_tables))
            {
                foreach($other_tables as $key => $values)
                {
                    $argu = $argu.$this->table.".".$values." = ".$key.".".$values.",";
                
                }
                //Removes the last comma
                $argu = substr($argu, 0, -1)." WHERE ";
            }
            
            //Adds the fields the parameters will correspond to
            $arr = array();
            foreach($params as $key => $value)
            {
               $argu = $argu.htmlentities($key)." = `:".htmlentities($value. "`,");
                
                $arr[":".$value] = $value;
            }
            unset($key);
            unset($value);
            
            //Removes the last comma
            $argu = substr($argu, 0, -1)."'";
            
            //Sends the information to the server
            $stmt = $this->pdo->prepare($argu);
            $stmt->execute($arr);
            return $stmt;
        }
        public function update($params, $where)
        {
            $argu = "UPDATE ".$table." SET ";
            
            //Adds the values to be changed
            foreach($params as $key => $values)
            {
                $argu = $argu.$key." = ".htmlentities($values).",";
            }
            unset($key);
            unset($values);
            
            //Removes extraneous commas
            $argu = susbtr($argu, 0, -1)." WHERE ";
            
            //The Where statement
            $arr = array();
            
            foreach($where as $key => $values)
            {
                $argu = $argu.$key."= :".htmlentities(values).",";
                
                $arr[":".$value] = $value;
            }
            unset($key);
            unset($values);
            
            //Removes the extraneous commas
            $argu = substr($argu, 0, -1);
            
            //Executes the sql
            $stmt = $this->pdo->prepare($argu);
            $stmt->execute($arr);
        }
        public function delete($where, $other_tables)
        {
            $argu = "DELETE ".$table." WHERE ";
            
            //The Where statement
            $arr = array();
            foreach($where as $key => $values)
            {
                $argu = $argu.$key."= :".htmlentities(values).",";
                
                $arr[":".$value] = $value;
            }
            unset($key);
            unset($values);
            
            //Removes extraneous commas
            $argu = susbtr($argu, 0, -1);
            
            //Executes the sql
            $stmt = $this->pdo->prepare($argu);
            $stmt->execute($arr);
        }
    }

    function basic_stuff()
    {
        if(isset($_POST['Cancel']))
        {
            header("Location: index.php");
            return;
        }
        if ( isset($_POST['Logout']) ) {
            echo("bloop");
            header('Location: logout.php');
            return;
        }    
    }
    function die_now()
    {
        if ( ! isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) < 1  ) {
            die('Access Denied');
        }    
    }
    function errors()
    {
        if(isset($_SESSION['error']))
        {
            echo('<p style="color:red;">'.$_SESSION['error']."</p>\n");
            unset($_SESSION['error']);
        }
    }

    //Validates for the add fields
    function validatePos()
    {
        for($i=1; $i<=9; $i++)
        {
           //Validates that that form exists, and if doesn't, the user isn't punished for that
            if(!isset($_POST['year'.$i])) continue;
            if(! isset($_POST['desc'.$i])) continue;
            
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
            
            if(strlen($year) == 0 || strlen($desc) == 0)
            {
                return "All fields are required";
            }
            if(! is_numeric($year))
            {
                return "Position year must be numeric";
            }
        }
        //This validates the institution not the position
        for($i=1; $i<=9; $i++)
        {
           //Validates that that form exists, and if doesn't, the user isn't punished for that
            if(!isset($_POST['yeartime'.$i])) continue;
            if(! isset($_POST['edu_school'.$i])) continue;
            
            $year = $_POST['yeartime'.$i];
            $desc = $_POST['edu_school'.$i];
            
            if(strlen($year) == 0 || strlen($desc) == 0)
            {
                return "All fields are required";
                
            }
            if(! is_numeric($year))
            {
                return "Institution year must be numeric";
            }
        }
        return true;
    }
?>
<html>
<head>
</head>
<body>
<script type="text/javascript" src="jquery.min.js">
</script>
</body>