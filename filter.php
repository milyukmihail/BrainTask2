<?php

require_once 'InBd/Connection.php';

class Filter
{
    /**
     * @var connection class Connection
     * @var price for request
     */
    public $connection, $price, $of, $before, $symbol, $quantity;

    /**
     * Filter constructor.
     * @param $price
     * @param $of
     * @param $before
     * @param $symbol
     * @param $quantity
     */
    public final function __construct($price, $of, $before, $symbol, $quantity)
    {
        $this->connection = new Connection();

        $this->price = $price;
        $this->of = $of;
        $this->before = $before;
        $this->symbol = $symbol;
        $this->quantity = $quantity;
    }

    /**
     * @return bool
     * Additional validation
     */
    private function checkValidation()
    {
        if ($this->price === "cost" || $this->price === "cost_wholesale") {
            if ($this->symbol === "bolee" || $this->symbol === "menee") {
                if (is_int($this->of)) {
                    if (is_int($this->before)) {
                        if (is_int($this->quantity)) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
    }

    /**
     * @return false|string(request)
     */
    private function chooseQuery()
    {
        $this->checkValidation();
        if ($this->symbol == 'bolee') {
            return "SELECT * FROM products.product WHERE (in_stock_1+in_stock_2)>? AND ?<"
                . $this->price . " AND " . $this->price . "<?";
        } elseif ($this->symbol == 'menee') {
            return "SELECT * FROM products.product WHERE (in_stock_1+in_stock_2)<? AND ?<"
                . $this->price . " AND " . $this->price . "<?";
        } else {
            return false;
        }
    }

    /**
     * @return false|mysqli_result
     */
    private function buildQuery()
    {
        $query = $this->connection->link->prepare($this->chooseQuery());
        $query->bind_param('iii', $this->quantity, $this->of, $this->before);
        $query->execute();
        return $query->get_result();
    }

    /**
     * @return false|string(json)
     */
    public final function getData()
    {
        $arr = $this->buildQuery()->fetch_all();
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

}

$filter = new Filter($_POST['price'], $_POST['of'], $_POST['before'], $_POST['symbol'], $_POST['quantity']);
echo $filter->getData();
