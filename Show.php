<?php

require_once 'InBd/Connection.php';

class Show
{
    /**
     * @var Connection
     */
    public $connection;

    /**
     * @connection type Connection
     */
    public function __construct()
    {
        $this->connection = new Connection();
    }

    /**
     * @param $request
     * @return mysqli_result
     */
    private function getData($request)
    {
        return mysqli_query($this->connection->link, $request);
    }

    /**
     * @param $request
     * @return one mixed value
     */
    public function getDataFromRequest($request)
    {
        $query = $this->getData($request);
        $result = mysqli_fetch_row($query);
        return $result[0];
    }

    /**
     * show table with all data
     */
    public function showTable()
    {
        $request = 'SELECT * FROM products.product;';
        $result = $this->getData($request);
        $mostExpensive = $this->getDataFromRequest('SELECT MAX(cost) from products.product;');
        $cheapest = $this->getDataFromRequest(
            'SELECT MIN(cost_wholesale) from products.product WHERE cost_wholesale>0;');

        if ($result) {

            $rows = mysqli_num_rows($result);

            echo '<table border="1"><tr><th>Id</th>' .
                '<th>Наименование товара</th>' .
                '<th>Стоимость, руб</th><th>Стоимость опт,руб</th>' .
                '<th>Наличие на складе 1, шт</th>' .
                '<th>Наличие на складе 2, шт</th>' .
                '<th>Страна производста</th>' .
                '<th>Примечание</th></tr>';

            for ($i = 0; $i < $rows; ++$i) {
                $row = mysqli_fetch_row($result);

                if (in_array($mostExpensive, $row)) {
                    echo "<tr bgcolor='red'>";
                } elseif (in_array($cheapest, $row)) {
                    echo "<tr bgcolor='green'>";
                } else {
                    "<tr>";
                }

                for ($j = 0; $j < 7; ++$j) {
                    echo "<td>$row[$j]</td>";
                }

                if ($row[4] + $row[5] < 20) {
                    echo "<td>Осталось мало!! Срочно докупите!!!</td>";
                }

                echo "</tr>";
            }
            echo "</table>";

            $this->connection->freeResult($result);
        }
    }

    /**
     * show additional data
     */
    public function showInfo()
    {
        $queryTotalInStock1 = "SELECT SUM(in_stock_1) FROM products.product;";
        $queryTotalInStock2 = "SELECT SUM(in_stock_2) FROM products.product;";
        $queryAveragePriceCost = "SELECT AVG(cost) FROM products.product;";
        $queryAveragePriceCostWholesale = "SELECT AVG(cost_wholesale) FROM products.product;";

        echo "<h4>Общее количество товаров на Складе1: "
            . $this->getDataFromRequest($queryTotalInStock1) . " шт.</h4>";
        echo "<h4>Общее количество товаров на Складе2: "
            . $this->getDataFromRequest($queryTotalInStock2) . " шт.</h4>";
        echo "<h4>Средняя стоимость розничной цены товара: "
            . round($this->getDataFromRequest($queryAveragePriceCost), 2) . " руб.</h4>";
        echo "<h4>Средняя стоимость оптовой цены товара: "
            . round($this->getDataFromRequest($queryAveragePriceCostWholesale), 2) . " руб.</h4>";
    }
}

