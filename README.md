<strong>Dbw</strong> - it`s a FrameWork for work with data bases, based on <strong>PDO</strong> connection.
<strong>Dependencies - PHP 5.3.* >=</strong>.

For now has capabilities to setup unlimmit connections types to one database or to several databases together, also 
setup database default connection settings inside class and override them if need, when calling the class.  

 It's very friendly with <strong>SQL</strong> methods like <strong>SELECT, INSERT, UPDATE, DELETE.</strong> and has method to convert ASCII chars after fetch, when working with hebrew data base

More features comming soon :)

<div style="align:center;font-weight:bold;"><h1>Getting Started</h1></div>

With override default config method:

<pre>
  require_once "autoloader.php";
  
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
</pre>

With default config: 
<pre>
 $db = \DBWork\Dbw::R();
</pre>


<strong>Letter R() represents Connection Type.</strong>


<h2>Working with SELECT method</h2>

<pre>

$db = \DBWork\Dbw::R();

$a = 1;
$b = 2;

$sql = "SELECT EmployeeID, LastName, FirstName, BirthDate, Address, City, Region FROM `Employees` 
        WHERE  EmployeeID IN (".$a.",".$b.",6,7)";

$db->select($sql);
</pre>

<strong>for activate debug before execute just add second parameter - true</strong>

<pre>
$db->select($sql, true);
</pre>


<h3>Fetch Methods </h3>

------------------------------- For while loops -----------------------------------

<pre>
$db->select($sql);
$row = $db->oFetch();
</pre>


<strong>$row = $db->fetch()</strong> // fetch assoc

<strong>$row = $db->oFetch()</strong> // fetch object

<strong>$row = $db->fetchClass('Class Name')</strong> //fetch class

<strong>$row = $db->fetchIntoClass(new Class())</strong>// fetch into class

<strong>$row = $db->fetchClassAfterConstr('Class Name')</strong> // fetch to class after constuctor


----------------------- Fetch All Without While Loops -------------------------------

<strong>$result = $db->fetchAll()</strong>

<strong>$result = $db->oFetchAll()</strong>

<strong>$result = $db->fetchClassAll('Class Name')</strong>

<strong>$result = $db->fetchClassAllAfterConstr('Class Name')</strong>

<strong>$result = $db->fetchAllIntoClass(new Class())</strong>

<h2>Working with UPDATE method</h2>

update method return affected rows count.

In update method very important to set all where conditions like <b>where, whereIn, whereBetween</b> with number in the end of each condition. Here is an Example:

<pre>

$arr = [];
$arr['titleOfCourtesy']   = "Mss.";


$result = $db->update('Employees', $arr)
             ->whereBetween1("EmployeeID", 2, 10)
             ->where1("Country", "USA")
             ->exec();

</pre>


<h3> WHERE Conditions </h3>

As you can see, each where condition comes with number in the end.
it's very important to setup where conditions with numbers, otherwise you will see errors.
If you want to pass more than 1 where or whereIn or whereBetween, respectively you can add them with number increment like this :

<pre>
where1()->where2()->where3();
</pre>
also you can pass a separator, by default is <b>"="</b>
To pass a differend separator just add third parameter to the where conditions like this :

<pre>

$arr = [];
$arr['titleOfCourtesy']   = "Mss.";


$result = $db->update('Employees', $arr)
             ->where1("Date", "2014-09-28", ">") //in sql you will se this like : where date > "2014-09-28"
             ->where2("Country", "USA", "<>")  //in sql you will se this like : where Countery <> "USA"
             ->exec();

</pre>


<h3> exec() Method </h3> 

exec method is executing the Query.
You only need to add him to the end of all your where conditions, and if you want to debug your Query
before execute, just pass - true in exec like this: 


<pre>

$arr = [];
$arr['titleOfCourtesy']   = "Mss.";


$result = $db->update('Employees', $arr)
             ->where1("Date", "2014-09-28", ">") //in sql you will se this like : where date > "2014-09-28"
             ->where2("Country", "USA", "<>")  //in sql you will se this like : where Countery <> "USA"
             ->exec(true);

</pre>

