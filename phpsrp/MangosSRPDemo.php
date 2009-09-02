<?php
error_reporting(E_ALL);

require_once("MangosSRP.class.php");

$sha_pass = "0D7361CC151A9B64339ED50E3849BE29F3045940";
$s = "97957AFB2038416C14B3EAAFF7D84A374A7CDC53716489E5218166FADA7B43C5";
$v = "6A0A36FF84F60FA2EC214C301E3D0130DB5B83B8335E1CD0D1BD0ADA9C3DEBCB";

$calculated = MangosSRP::calculateV($s, $sha_pass);

echo "calculated: $calculated\n";
echo "expected:   $v\n";
echo "Test result:\t";
if(strtolower($calculated) == strtolower($v))
    echo "[OK]";
else
    echo "[FAILED]";
echo "\n";
?>
