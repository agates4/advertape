<?
    require __DIR__ . '/vendor/autoload.php';

    # This function reads your DATABASE_URL configuration automatically set by Heroku
    # the return value is a string that will work with pg_connect
    function pg_connection_string() {
        $dbopts = parse_url(getenv('DATABASE_URL'));
        return "dbname=".ltrim($dbopts["path"],'/')." host=".$dbopts["host"]." port=".$dbopts["port"]." user=".$dbopts["user"]." password=".$dbopts["pass"]." sslmode=require";
    }

    echo "<br>";

    if (isset($_GET["code"])) {
        $decryptedCode = openssl_decrypt($_GET["code"], parse_url(getenv('ENCRYPT_METHOD'))["path"], parse_url(getenv('ENCRYPT_PASS'))["path"], false, parse_url(getenv('ENCRYPT_IV'))["path"]);
        $id = explode("&&&&", $decryptedCode)[1];
        echo $id;

        # Establish db connection
        $db = pg_connect(pg_connection_string());
        if (!$db) {
            echo "Database connection error.";
            exit;
        }
        
        $result = pg_query($db, "SELECT * FROM ads WHERE id = " . $id . ";");

        while ($row = pg_fetch_row($result)) {
            var_dump($row);
            echo "<br />\n";
        }
    }
    // var_dump(openssl_encrypt("Winner winner!&&&&2", parse_url(getenv('ENCRYPT_METHOD'))["path"], parse_url(getenv('ENCRYPT_PASS'))["path"], false, parse_url(getenv('ENCRYPT_IV'))["path"]));

    

    // echo $_GET["code"];

?>