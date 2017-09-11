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

        if ($qr == "t") {
            if ($used == "t") {
                echo "o shit this one is used";
            }
            else {
                print "<div class=\"container\">\n";
                print "    <div class=\"columns\">\n";
                print "        <div class=\"column col-xl-10\" style=\"margin:0 auto; max-width: 500px;\">\n";
                print "            <div class=\"card\">\n";
                print "                <div class=\"card-header\">\n";
                print "                    <div class=\"card-title h5\">$name</div>\n";
                print "                    <div class=\"card-subtitle text-gray\">Wendy's</div>\n";
                print "                </div>\n";
                print "                <div class=\"card-image\">\n";
                print "                    <img class=\"img-responsive\" src=\"resources/img/wendys.png\" alt=\"Wendy's!\">\n";
                print "                </div>\n";
                print "                <div class=\"card-body\">\n";
                print "                    $value\n";
                print "                </div>\n";
                print "                <div class=\"card-footer\">\n";
                print "                    <div class=\"btn-group btn-group-block\">\n";
                print "                        <button id=\"activate\" class=\"btn\">Activate</button>\n";
                print "                    </div>\n";
                print "                </div>\n";
                print "            </div>        \n";
                print "        </div>\n";
                print "    </div>\n";
                print "</div>\n";
                print "\n";
                print "<script>\n";
                print "\n";
                print "function getUrlVars() {\n";
                print "    var vars = {};\n";
                print "    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    \n";
                print "    function(m,key,value) {\n";
                print "        vars[key] = value;\n";
                print "    });\n";
                print "    return vars;\n";
                print "}\n";
                print "\n";
                print "// Bind to the submit event of our form\n";
                print "$(\"#activate\").click(function(event){\n";
                print "    // Prevent default posting of form - put here to work in case of errors\n";
                print "    event.preventDefault();\n";
                print "    $(\"#activate\").addClass(\"loading\");\n";
                print "    $(\"#activate\").addClass(\"btn-primary\");\n";
                print "\n";
                print "    setTimeout(function(){\n";
                print "        // Fire off the request to /useQR.php\n";
                print "        request = $.ajax({\n";
                print "            url: \"/useQR.php\",\n";
                print "            type: \"post\",\n";
                print "            data: {code: getUrlVars()[\"code\"]}\n";
                print "        });\n";
                print "\n";
                print "        // Callback handler that will be called on success\n";
                print "        request.done(function (response, textStatus, jqXHR){\n";
                print "            // Log a message to the console\n";
                print "            console.log(\"Hooray, it worked!\");\n";
                print "            console.log(response);\n";
                print "            $(\"#activate\").removeClass(\"loading\");\n";
                print "            // $(\"#activate\").html(\"Cashier - tap to approve!\");\n";
                print "            $(\"#activate\").prop(\"disabled\", true);\n";
                print "            alert(\"Congrats! Your coupon has been activated! Show this to your cashier to redeem the coupon.\")";
                print "        });\n";
                print "\n";
                print "        // Callback handler that will be called on failure\n";
                print "        request.fail(function (jqXHR, textStatus, errorThrown){\n";
                print "            // Log the error to the console\n";
                print "            console.error(\n";
                print "                \"The following error occurred: \"+\n";
                print "                textStatus, errorThrown\n";
                print "            );\n";
                print "        });\n";
                print "    }, 1000);\n";
                print "\n";
                print "});\n";
                print "\n";
                print "</script>";
                            
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
