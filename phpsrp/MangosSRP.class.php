<?php
/*
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

class MangosSRP
{
    private static $g = "7";
    public  static $N;

    public static function staticConstructor()
    {
        self::$N = gmp_init("894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7", 16);
    }

    private static function hexDecode($str)
    {
        if (strlen($str)&1)
            throw new exception("strlen must be straight");

        $out="";
        for($i=0; $i<strlen($str);++$i)
        {
            $highChar = $str[$i];
            $lowChar = $str[++$i];
            $highCharValue = base_convert($highChar, 16, 10);
            $lowCharValue = base_convert($lowChar, 16, 10);
            $byteValue = $highCharValue<<4;
            $byteValue |= $lowCharValue;
            $byte = chr($byteValue);
            $out.=$byte;
        }
        return $out;
    }

    private static function hexEncode($bytearray)
    {
        $out="";
        for($i=0; $i<strlen($bytearray);++$i)
        {
            $char = $bytearray[$i];
            $charvalue = ord($char);
            $encoded = base_convert($charvalue, 10, 16);
            if (strlen($encoded)==1)
                $encoded = "0$encoded";
            $out.=strtoupper($encoded);
        }
        return $out;
    }

    public static function calculateV($s, $sha_pass_hash)
    {
        $s = self::hexDecode($s);
        $sha_pass_hash = self::hexDecode($sha_pass_hash);

        if (strlen($s) != 32 || strlen($sha_pass_hash) != 20)
            throw new exception("calculateV: invalid argument");

        $x = self::hexEncode(strrev(sha1(strrev($s).$sha_pass_hash, true)));
        $v = gmp_powm(self::$g, gmp_init($x, 16), self::$N);
        $strval= gmp_strval($v, 16);
        // append leading zeros
        while(strlen($strval)<64)
        {
            $strval = "0".$strval;
        }
        return $strval;
    }

    public static function calculateShaPassHash($username, $password)
    {
        return sha1(strtoupper($username).':'.strtoupper($password));
    }

    public static function generateNewS()
    {
        // S is a random 32 byte array
        // gmp_random generates 16 bit per limiter; 32byte/16bit = 32*8/16 = 16
        $random = gmp_random(16);
        $random_str = gmp_strval($random, 16);
        $s_str = sha1($random_str);
        // sha1 is just 20 bytes long, add 12 more bytes = 24 (hexencoded)
        $s_str.=substr($random_str, 0, 24);

        return $s_str;
    }

    public static function registerNewUser($username, $password)
    {
        $returnValues = array();
        $returnValues['sha_pass_hash'] = self::calculateShaPassHash($username, $password);
        $returnValues['s'] = self::generateNewS();
        $returnValues['v'] = self::calculateV($returnValues['s'], $returnValues['sha_pass_hash']);

        return $returnValues;
    }

    public static function isValidPassword($username, $password, $s, $v)
    {
        // verify that v is correct
        $sha_pass_hash = self::calculateShaPassHash($username, $password);
        $correct_v = self::calculateV($s, $sha_pass_hash);
        if(strtoupper($correct_v) == strtoupper($v))
            return 1;
        else
            return 0;
    }
}

// php sucks, there are no static constructors, so just call this function after
// class definition
MangosSRP::staticConstructor();

?>
