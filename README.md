<strong>Dbw</strong> - A FrameWork for working with databases, based on <strong>PDO</strong> connection.
<strong>Dependencies - PHP 5.3.* >=</strong>.

For now has capabilities to setup unlimited connection types to one or several databases.

It's also possible to setup default connection settings inside the class and override them if needed, when calling the class.

It's very friendly with <strong>SQL</strong> methods like <strong>SELECT, INSERT, UPDATE, DELETE.</strong> and has method to convert ASCII chars after fetch, when working with hebrew data

More features coming soon :)

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


update method expects two parameters, first - Table Name,  second associative array, and return affected rows count.

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
             ->where2("Country", "USA")  //in sql you will se this like : where Country "USA"
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
             ->where1("Date", "2014-09-28", ">") //in sql you will se this like : where Date > "2014-09-28"
             ->where2("Country", "USA", "<>")  //in sql you will se this like : where Country <> "USA"
             ->exec(true);

</pre>

<h2>Working with INSERT method</h2>

insert method looks the same as Update, exclude where conditions, he simply doesn't have them
insert method expects to parameters , first - Table Name ,  second associative array, and returning affected rows count. here is an example :

<pre>
$arr = [];
$arr['FirstName']   = "Vivid";
$arr['LastName']   = "Coches";
$arr['BirthDate']   = date("Y-m-d H:i:s");

$result2 = $db->insert('Employees', $arr);
</pre>

If you want to debug your query before execute it, just pass third parameter true
<pre>
insert('Employees', $arr, true);
</pre>

To get last insert id use <b>$db->getLastInsertId()</b>
But always remember, when debug is on, lastInsertId throwing exception,because there are no executed query.
here is an example :

<pre>
$arr = [];
$arr['FirstName']   = "Vivid";
$arr['LastName']   = "Coches";
$arr['BirthDate']   = date("Y-m-d H:i:s");

$result2 = $db->insert('Employees', $arr);
$lastID =  $db->getLastInsertId();
</pre>


<h2>Working with DELETE method</h2>

Delete method expects only table name, and then where conditions like in Update method. it's work in the same way as Update. 

Returning count of deleted rows. 
By default limit is set to one.

here is an Example :

<pre>
 $result3 = $db->delete('Employees')->where1("EmployeeID", 34)->exec();
</pre>

For Debug just pass true in exec method

<pre>
 $result3 = $db->delete('Employees')->where1("EmployeeID", 34)->exec(true);
</pre>

If you want change limit, you can do this with this method <b>setLimit(54)</b>

Here is an Example :

<pre>
 $db->setLimit(54);
 $result3 = $db->delete('Employees')->where1("EmployeeID", 34)->exec(true);
</pre>

