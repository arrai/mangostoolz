<?php
error_reporting(E_ALL);

require_once("MangosSRP.class.php");

//////
// TESTING AREA

// these are expected values to perform 
define("TEST_USERNAME", "administrator");
define("TEST_PASSWORD", "wa");
define("TEST_SHA_PASS_HASH", "0D7361CC151A9B64339ED50E3849BE29F3045940");
define("TEST_S", "97957AFB2038416C14B3EAAFF7D84A374A7CDC53716489E5218166FADA7B43C5");
define("TEST_V", "6A0A36FF84F60FA2EC214C301E3D0130DB5B83B8335E1CD0D1BD0ADA9C3DEBCB");
define("TEST_FALSE_V", "6A0A36FF84F60FABEC214C301E3D0130DB5B83B8335E1CD0D1BD0ADA9C3DEBCB");
##############
echo "Checking MangosSRP::calculateV\n";
$calculated = MangosSRP::calculateV(TEST_S, TEST_SHA_PASS_HASH);

echo "calculated: $calculated\n";
echo "expected:   ".TEST_V."\n";
echo "Test result:\t";
if(strtolower($calculated) == strtolower(TEST_V))
    echo "[OK]";
else
    echo "[FAILED]";
echo "\n\n";
##############
echo "Checking MangosSRP::calculateShaPassHash\n";
$calculated = MangosSRP::calculateShaPassHash(TEST_USERNAME, TEST_PASSWORD);

echo "calculated: $calculated\n";
echo "expected:   ".TEST_SHA_PASS_HASH."\n";
echo "Test result:\t";
if(strtolower($calculated) == strtolower(TEST_SHA_PASS_HASH))
    echo "[OK]";
else
    echo "[FAILED]";
echo "\n\n";
##############
echo "Checking MangosSRP::registerNewUser\n";
$calculated = MangosSRP::registerNewUser(TEST_USERNAME, TEST_PASSWORD);

echo "calculated: ";
var_dump($calculated);
echo "expected:   ".TEST_SHA_PASS_HASH."\n";
echo "Test result:\t";
if(strtolower($calculated['sha_pass_hash']) == strtolower(TEST_SHA_PASS_HASH))
    echo "[OK]";
else
    echo "[FAILED]";
echo "\n\n";
##############
echo "Checking MangosSRP::isValidPassword\n";
$correct = MangosSRP::isValidPassword(TEST_USERNAME, TEST_PASSWORD, TEST_S, TEST_V);
$incorrect = MangosSRP::isValidPassword(TEST_USERNAME, TEST_PASSWORD, TEST_S, TEST_FALSE_V);

echo "calculated: $correct, $incorrect\n";
echo "expected:   true, false\n";
echo "Test result:\t";
if($correct && !$incorrect)
    echo "[OK]";
else
    echo "[FAILED]";
echo "\n\n";

// TESTING AREA
//////

?>
