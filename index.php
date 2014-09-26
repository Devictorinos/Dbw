<?php

require_once "autoloader.php";

class Employee
{
    public $EmployeeID;
    public $LastName;
    public $FirstName;
    public $BirthDate;
    public $Address;
    public $City;
    public $Region;


    public function __construct()
    {

    }

    public function getEmD()
    {
        echo "Employee First Name " . $this->FirstName;
        echo "<hr/>";
        echo "Employee Last Name " . $this->LastName;
        echo "<hr/>";
        echo "Employee Birth Date " . $this->BirthDate;
        echo "<hr/>";
        echo "lives in City " . $this->City . " and Address " . $this->Address;
        echo "<hr/>";
        echo "Employee Region " . $this->Region;
        echo "<br><br><br><br>";

    }
}




$a = 1;
$b = 2;

//$employees = new Employee();

$sql = "SELECT EmployeeID, LastName, FirstName, BirthDate, Address, City, Region FROM `Employees` 
        WHERE  EmployeeID IN (".$a.",".$b.",6,7)";

$config  = array('host'    => "localhost",
                 'user'    => "root",
                 'pass'    => "123",
                 'dbname'  => "northwind",
                 'options' => array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                            PDO::ATTR_TIMEOUT => 1000,
                        )
                 );

$db = \DBWork\Dbw::R($config);

$db->select($sql, true);


while ($row = $db->FetchIntoClass(new Employee())) {
    echo "<pre>";
    var_dump($row);
    echo "</pre>";

}

/*$sql = "SELECT EmployeeID, LastName, FirstName, BirthDate, Address, City, Region FROM `Employees` 
        WHERE  EmployeeID IN (".$a.",".$b.",8,9)";

Dbw::R()->select($sql);


while ($row = Dbw::R()->FetchIntoClass(new Employee())) {
    echo "<pre>";
    var_dump($row);
    echo "</pre>";

}*/

/*$result = Dbw::R()->fetchClassAll('Employee');

foreach ($result as $key => $value) {
    $value->getEmD();
}
*/


$arr['titleOfCourtesy']   = "Mrr.";


$result = $db->update('Employees', $arr)->whereBetween1("EmployeeID", 1, 8)->where1("Country", "USA", "=")->exec(true);

var_dump($result);



$arr = [];

$arr['titleOfCourtesy']   = "Mss.";


$result = $db->update('Employees', $arr)->whereBetween1("EmployeeID", 2, 10)->where1("Country", "USA", "<>")->exec(true);

var_dump($result);
$arr = [];
$arr['FirstName']   = "Vivid";
$arr['LastName']   = "Coches";
$arr['BirthDate']   = date("Y-m-d H:i:s");

$result2 = $db->insert('Employees', $arr, true);
//$lastID = Dbw::R()->getLastInsertId();
//var_dump($result2);
//var_dump($lastID);



/*$arr = [];
$arr['FirstName']   = "fdfdf";
$arr['LastName']   = "fdfdfdfdfd";
$arr['BirthDate']   = date("Y-m-d H:i:s");

$result2 = Dbw::R()->insert('Employees', $arr);
$lastID = Dbw::R()->getLastInsertId();
var_dump($result2);
var_dump($lastID);*/
/*$arr['LastName']   = "Alexanderov";
$arr['FirstName']  = "Gudvin";


Dbw::R()->update('Employees', $arr);
$result2 = Dbw::R()->where1("EmployeeID", 2)->exec();*/

//var_dump($result2);

/*$arr['LastName']   = "Lubchuk";
$arr['FirstName']  = "Victor";*/


//$result = Dbw::R()->update('Employees', $arr)->where1("tyryr", 6)->whereBetween1("EmployeeID", 1, 6)->whereBetween2("DATE", "2014-09-18", "2014-12-12")->where3("tyryr", 6)->where4("fdsfsd", 8)->where5("Country", "gfdgd", ">")->where2("Country", "USA", "=")->exec(true);
//Dbw::R()->setLimit(54);
/*$result3 = Dbw::R()->delete('Employees')->where1("EmployeeID", 34)->exec(true);
var_dump($result3);*/
//$result2 = Dbw::R()->where1("EmployeeID", 2)->exec(true);