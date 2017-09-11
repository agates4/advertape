<?

$code = isset($_POST['bar']) ? $_POST['bar'] : null;

if ($code != null) {
    echo $code;
}
else {
    echo "o shit";
}

?>