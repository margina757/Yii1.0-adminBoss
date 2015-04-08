<?php
/**
 * Salt encrypt Password Class
 * @example Salt::generateSalt( password )
 * @author Howe Isamu <xi4oh4o@gmail.com>
 */
class salt
{
    /**
     * generateSalt Function
     * using generate random salt confusing password.
     * and save confusing password.
     * @param $salt integer 1/10 Million random Num
     * @param $hash string md5 encrypt password
     * @param $salt_hash string salt+hash after md5 encrypt
     * @return array salt and hash
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function generateSalt($passwd)
    {
        $salt          = mt_rand( 100000, 999999 );
        $hash          = md5($passwd);
        $salt_hash     = md5($salt.$hash);
        return $result=array('salt'=>$salt, 'hash'=>$salt_hash);
    }
    /**
     * vertifySalt Function
     * use $salt and $passwd generate salt_hash
     * @return string encrypted hash
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function vertifySalt($passwd , $salt)
    {
        $salt              = $salt;
        $hash              = md5($passwd);
        $salt_hash         = md5($salt.$hash);
        return $salt_hash;
    }
}
