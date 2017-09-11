<?
    require __DIR__ . '/vendor/autoload.php';

    # This function reads your DATABASE_URL configuration automatically set by Heroku
    # the return value is a string that will work with pg_connect
    function pg_connection_string() {
        $dbopts = parse_url(getenv('DATABASE_URL'));
        return "dbname=".ltrim($dbopts["path"],'/')." host=".$dbopts["host"]." port=".$dbopts["port"]." user=".$dbopts["user"]." password=".$dbopts["pass"]." sslmode=require";
    }
    
    # Establish db connection
    $db = pg_connect(pg_connection_string());
    if (!$db) {
        echo "Database connection error.";
        exit;
    }
    
    $result = pg_query($db, "SELECT * FROM ads");

    while ($row = pg_fetch_row($result)) {
        var_dump($row);
        echo "<br />\n";
    }

    echo "<br>";

    var_dump(parse_url(getenv('ENCRYPT_METHOD')));
    var_dump(openssl_encrypt("Winner winner!&&&&2", parse_url(getenv('ENCRYPT_METHOD')), parse_url(getenv('ENCRYPT_PASS')), false, parse_url(getenv('ENCRYPT_IV'))));


    // echo $_GET["code"];

?>