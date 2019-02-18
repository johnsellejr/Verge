<?PHP
/**

 This PHP script executes basic tasks on a CouchDB server

 For this to work, you have to tell the database DSN (example : http://couch.server.com:5984/) and the name of a database that does not exist

*/

 //Setup an autoloader (using src/autoload.php)
 $srcDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'verge' . DIRECTORY_SEPARATOR . 'vendor';
 require $srcDir . DIRECTORY_SEPARATOR . 'autoload.php';

### ANON DSN
//$couchDsn = "http://localhost:5984/";
### AUTHENTICATED DSN
$couchDsn = "http://www.johnselle.com:15984/";
//$couchDsn = "http://jsellejr:Abc123!@www.johnselle.com:15984/";
$couchDB = "verge";
$couchDBvars = ['username'=>'jsellejr','password'=>'Abc123!','cookie_auth'=>TRUE];
//$couchDBvars = ['cookie_auth'=>TRUE];

//Import required libraries
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions\CouchException;

/**
* create the client
*/
$client = new CouchClient($couchDsn,$couchDB,$couchDBvars);
//$client = new CouchClient($couchDsn,$couchDB);
//$cookie = $client->setSessionCookie("AuthSession=anNlbGxlanI6NUM2OTdGQjg6bWi7hY03V7TpxHdx8T6jeGjkAro")->getSessionCookie();

//$queryParams = http_build_query(['name' => 'jsellejr', 'password' => 'Abc123!']);
//echo $queryParams . "\r\n";
//$rawData = $client->query('POST', '/_session', null, $queryParams, 'application/x-www-form-urlencoded');
//echo $rawData;

//list($headers) = explode("\r\n\r\n", $rawData, 2);
//echo $headers;
//$headersArray = explode("\r\n", $headers);
//print_r($headersArray);

/**
foreach ($headersArray as $line) {
    if (strpos($line, 'Set-Cookie: ') === 0) {
        $line = substr($line, 12);
        print_r($line);
        $line = explode('; ', $line, 2);
        print_r($line);
        print reset($line);
        $client->setSessionCookie(reset($line));
        break;
    }
}
**/
echo "Trying storeDoc functions \r\n";
//print 'This should be the cookie: ' . $client->getAllDocs() . "\r\n";
//print "Here's our session cookie: " . $client->getSessionCookie() . "\r\n";
print "Here's our session cookie: " . $cookie . "\r\n";


try {
    //session_start();
    //Let's try and store a document
    $new_doc = new stdClass();
    $new_doc->_id = $client->getUuids(1)[0];
    $new_doc->date_created = date('r');
    $new_doc->user = 'jsellejr';
    $new_doc->type = 'post';
    $new_doc->content = 'Sample Post from testPOC Script';
    
    echo "Here's the DOM\r\n";
    var_dump($new_doc);
    echo json_encode($new_doc);
    $response = $client->storeDoc($new_doc);
    
    //print json_encode($client->getAllDocs()) . "\r\n";    
} catch (Exception $e) {
    echo "\r\nSomething weird happened: " . print_r($e->getMessage());
    exit();
}

//echo var_dump($response);

echo "\r\nTrying getView functions \r\n";

try {
    //session_start();
    //print 'This should be the cookie: ' . $client->getAllDocs() . "\r\n";
    print "Here's our session cookie: " . $client->getSessionCookie() . "\r\n";

    //$posts = array();
    $response = $client->key('jsellejr')->descending(true)->reduce(false)->skip(0)->limit(10)->getView('application', 'posts_by_user');
    //$response = $client->key('jsellejr')->reduce(true)->getView('application', 'posts_by_user');
    /*
    for ($i = 0; $i < $response->total_rows; $i++) {
        array_push($posts, $response->rows[$i]->value);
    } */
    //$rows = $response->rows[0]->value;
    //print_r($posts);
    print_r($response);
    //echo "\r\nHere are number of rows: " . $rows . "\r\n"; 
    
} catch (Exception $e) {
    echo "Something weird happened: " . print_r($e->getMessage());
    exit();
}

echo "\r\nTrying deleteDoc functions \r\n";

//Retrieve the document to be deleted
try {
    $doc = $client->rev("1-9f9b57d8c27cb63681a5f382a2883b2a")->getDoc("4f47890d4458b38a8a21180f45039411");
    print_r($doc);
} catch (Exception $e) {
    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
    exit;
}

try {
    $client->deleteDoc($doc);
} catch (Exception $e) {
    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
    exit;
}

//echo var_dump($response);

/**
* first of all, let's list databases on the server.
* This ensure server connectivity and that $couchDB does not exist
*
* note the use of a "try {} catch () {}" block to allow gracefull error handling
*


echo 'Getting databases infos : $databases = $client->listDatabases();'."\n";

try {
	$databases = $client->listDatabases();
} catch ( Exception $e) {
	echo "Some error happened during the request. This is certainly because your couch_dsn ($couchDsn) does not point to a CouchDB server...\n";
	exit(1);
}
echo "Database list fetched : \n".print_r($databases,true)."\n";

if ( in_array($couchDB,$databases) ) {
	echo "Database $couchDB already exist. Please drop it or edit this script and set $couchDB to a non-existant database\n";
	//exit(1);
}
*/

echo "Getting database informations\n";
try {
	$db_infos = $client->getDatabaseInfos();
} catch (Exception $e) {
	echo "Something weird happened  :".$e->getMessage()." (errcode=".$e->getCode().")\n";
	exit(1);
}
echo "Database informations : \n".print_r($db_infos,true)."\n";
echo "Displaying database disk usage using \$db_infos->disk_size: ".$db_infos->disk_size." bytes\n";


echo "\nTo learn more database features : https://github.com/PHP-on-Couch/PHP-on-Couch/blob/master/doc/database.rst \n";
