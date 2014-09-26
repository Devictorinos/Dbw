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
