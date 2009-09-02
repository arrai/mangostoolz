<?php
/*
A simple script to demonstrate how to send mails with attached
items on mangos
Copyright (C) 2009 arrai

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function sanitizeArg($arg)
{
    return str_replace(array("\n", "\""), "", $arg);
}

function easyMail($username, $password, $playername, $subject, $text, $items, $host="localhost", $port=3443)
{
    // sanitize arguments
    $playername = sanitizeArg($playername);
    $subject = sanitizeArg($subject);
    $text = sanitizeArg($text);
    
    foreach($items as $k=>$v)
        $item_sanit[intval($k)]=intval($v);

    $connection = fsockopen($host, $port, $error, $error_str, 5);
    if($connection===FALSE)
        return $error_str;


    // read welcome
    $answer="";
    while(($c = fread($connection ,1)) && $c!="\n")
    {
        $answer.=$c;
    }
    if(!fwrite($connection, "USER $username\n") || !fwrite($connection, "PASS $password\n"))
        return "error while writing to socket";

    $answer="";
    while(($c = fread($connection ,1)) && $c!="\n")
    {
        $answer.=$c;
    }

    if(!strstr($answer, "Logged in."))
        return $answer;

    $command = "send items $playername \"$subject\" \"$text\"";
    foreach($item_sanit as $k=>$v)
        $command.=" $k:$v";
    $command.="\n";

    fwrite($connection, $command);

    $answer="";
    while(($c = fread($connection ,1)) && $c!="\n")
    {
        $answer.=$c;
    }
    
    return $answer;
}



// demonstration
$subject = "Greetings";
$text = "In recongintion of your efforts for nation and people, I'm proud to give you those items!11";
// items is an associative array of itemid=>count
$items = array(40653=>1, 17197=>5);

$result = easyMail("administrator", "mysecretpass", "Shadowdeathx", $subject, $text, $items);

echo $result."\n";
?>

