<?
    require __DIR__ . '/vendor/autoload.php';

    $ENUM_ID = 0;
    $ENUM_NAME = 1;
    $ENUM_VALUE = 2;
    $ENUM_CAMP_ID = 3;
    $ENUM_USED = 4;
    $ENUM_QR = 5;
    $ENUM_DATE_USED = 6;

    # This function reads your DATABASE_URL configuration automatically set by Heroku
    # the return value is a string that will work with pg_connect
    function pg_connection_string() {
        $dbopts = parse_url(getenv('DATABASE_URL'));
        return "dbname=".ltrim($dbopts["path"],'/')." host=".$dbopts["host"]." port=".$dbopts["port"]." user=".$dbopts["user"]." password=".$dbopts["pass"]." sslmode=require";
    }

    if (isset($_GET["code"])) {
        $decryptedCode = openssl_decrypt($_GET["code"], parse_url(getenv('ENCRYPT_METHOD'))["path"], parse_url(getenv('ENCRYPT_PASS'))["path"], false, parse_url(getenv('ENCRYPT_IV'))["path"]);
        $id = explode("&&&&", $decryptedCode)[1];

        # Establish db connection
        $db = pg_connect(pg_connection_string());
        if (!$db) {
            echo "Database connection error.";
            exit;
        }
        
        $result = pg_fetch_row(pg_query($db, "SELECT * FROM ads WHERE id = " . $id . ";"));

        $name = $result[$ENUM_NAME];
        $value = $result[$ENUM_VALUE];
        $used = $result[$ENUM_USED];
        $qr = $result[$ENUM_QR];
        $date_used = $result[$ENUM_DATE_USED];

        echo $qr;
        
    }
    // var_dump(openssl_encrypt("Winner winner!&&&&2", parse_url(getenv('ENCRYPT_METHOD'))["path"], parse_url(getenv('ENCRYPT_PASS'))["path"], false, parse_url(getenv('ENCRYPT_IV'))["path"]));

    

    // echo $_GET["code"];

?>