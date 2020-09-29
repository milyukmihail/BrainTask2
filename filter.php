<?php

require_once 'InBd/Connection.php';

class Filter
{
    public $connection, $price, $of, $before, $znak, $quantity;

    public function __construct($price, $of, $before, $znak, $quantity)
    {
        $this->connection = new Connection();

        $this->price = $price;
        $this->of = $of;
        $this->before = $before;
        $this->znak = $znak;
        $this->quantity = $quantity;
    }

    public function chooseQuery()
    {
        if ($this->znak == 'bolee'){
            return "SELECT * FROM products.product WHERE (in_stock_1+in_stock_2)>? AND ?<" . $this->price . " AND " . $this->price . "<?";
        } elseif ($this->znak == 'menee') {
            return "SELECT * FROM products.product WHERE (in_stock_1+in_stock_2)<? AND ?<" . $this->price . " AND " . $this->price . "<?";
        } else { return false; }
    }

    public function bildQuery()
    {
        $query = $this->connection->link->prepare($this->chooseQuery());
        $query->bind_param('iii', $this->quantity, $this->of, $this->before);
        $query->execute();

        return $query->get_result();
    }

    public function getData()
    {
        $arr = $this->bildQuery()->fetch_all();
        $arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return $arr;
    }

}


$filter = new Filter($_POST['price'], $_POST['of'], $_POST['before'], $_POST['znak'], $_POST['quantity']);



print_r($filter->getData());
