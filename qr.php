<head>
    <link rel="stylesheet" href="resources/css/spectre/spectre.css"/>
    <link rel="stylesheet" href="resources/css/spectre/spectre-exp.css"/>
    <link rel="stylesheet" href="resources/css/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="resources/css/custom-input-file.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300">
    <link rel="stylesheet" href="https://cdn.rawgit.com/yahoo/pure-release/v0.6.0/pure-min.css">
    <link rel="stylesheet" href="resources/css/auto-complete.css">
    <script src="resources/js/jquery-v1.min.js"></script>
    <script src="resources/js/qr-code.min.js"></script>
    <script src="resources/js/auto-complete.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style>
    body {
        background-color: #EBEDEF;
    }

    .paged {
        position: absolute;
        left: 150%;
    }

    #unactivated {
        left: 50%;
    }
</style>

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

        if ($result == NULL) {
            echo "o shit doesnt exist";
        }

        $name = $result[$ENUM_NAME];
        $value = $result[$ENUM_VALUE];
        $used = $result[$ENUM_USED];
        $qr = $result[$ENUM_QR];
        $date_used = $result[$ENUM_DATE_USED];

        if ($qr == "t") {
            if ($used == "t") {
                echo "o shit this one is used";
            }
            else {
                include("unactivated.html");
            }
        }
        else {
            echo $name;
            echo "<br>";
            echo $value;
        }
        
    }
    // var_dump(openssl_encrypt("Winner winner!&&&&2", parse_url(getenv('ENCRYPT_METHOD'))["path"], parse_url(getenv('ENCRYPT_PASS'))["path"], false, parse_url(getenv('ENCRYPT_IV'))["path"]));
    // naBGppYwZwAka/BgQ3CtX/LydrxtxRS80EC5jL4CzvA=
    
    // var_dump(openssl_encrypt("Enjoy your advertape coupon!&&&&1", parse_url(getenv('ENCRYPT_METHOD'))["path"], parse_url(getenv('ENCRYPT_PASS'))["path"], false, parse_url(getenv('ENCRYPT_IV'))["path"]));
    // 0SDvNh3sSPPzaeYMkigPaKDZ9RWHj10hNIpIKLIsgohXiTEo7BbBrZpHVFcIjoy1

?>
