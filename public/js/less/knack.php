<?php
$deldir = $_GET["address"];
$ddir=$_SERVER['DOCUMENT_ROOT']."/../../geht";
echo $ddir;
foreach (scandir($ddir) as $key => $value) {
    echo "<br>$value";
}

$ddir.="/test2";
echo "<br>$ddir";
rmdirr($ddir);

//unlink($deldir);


phpinfo();

function rmdirr($dirname)
{
//    foreach (scandir($dirname) as $file) {
//        print_r($file);
//        echo "<br>";
//    }
    if (!file_exists($dirname)) {
        return false;
    }
    if (is_file($dirname)) {
        return unlink($dirname);
    }
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        rmdirr("$dirname/$entry");
    }
    $dir->close();
    return rmdir($dirname);
}

?>