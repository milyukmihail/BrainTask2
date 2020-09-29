<?php

class Connection
{
    public $link;

    /**
     * Connection constructor.
     */
    final public function __construct(){
        $host = 'localhost';
        $database = 'products';
        $user = 'root';
        $password = '';

        $this->link = mysqli_connect($host, $user, $password, $database)
        or die("Ошибка " . mysqli_error($this->link));

        $this->link->set_charset("utf8");
    }

    /**
     * Close connect
     */
    final public function closeConnect()
    {
        mysqli_close($this->link);
    }

    /**
     * freeResult
     * @result mixed
     */
    final public function freeResult($result)
    {
        mysqli_free_result($result);
    }
}
