<?php
require_once('view/queries/Queries.php');

class OrdersPage{
    private $dbHandler;
    private $queries;

    function __construct($dbHandler){
        $this->dbHandler = $dbHandler;
        $this->queries = new Queries();
    }

    public function getPageContent(){
        $queryOrderDetails = $this->queries->getOrderDetails();
        $orderDetails = $this->dbHandler->fetchArray($queryOrderDetails);
        
        $html = '<div class="order-list"><ol>';
        foreach($orderDetails as $order){
            $productsQuery = $this->queries->getOrderProducts($order['customer_id'], $order['date']);
            $products = $this->dbHandler->fetchArray($productsQuery);
            $productsList = $this->listProducts($products);
            
                    
            $html .= 
            '<hr>
            <li>
                <ul class="order-details">
                    <li>datum: '.$order['date'].'</li>
                    <li>produkter: '.$productsList.'</li>
                    <li>Kund detaljer: 
                        <ul class="no-style">
                            <li><b>namn:</b> '.$order['firstname'].' '.$order['lastname'].'</li>
                            <li><b>adress:</b></li>
                            <li>'.$order['address'].'</li>
                            <li>'.$order['zip'].', '.$order['city'].'</li>
                            <li>'.$order['country'].'</li>
                            <li><b>email:</b> '.$order['email'].'</li>
                        </ul>
                    </li>
                </ul>
            </li>';
        }

        return $html . '</ol></div>';

    }

    private function listProducts($products){
        $html = '<ol>';
        foreach($products as $p){
            $html .= 
            '<li>
                <ul class="order-item">
                    <li class="title">'.$p['name'].'</li>
                    <li class="no-style">produkt id:'.$p['product_id'].'</li>
                    <li class="no-style">strl: '.$p['size'].'</li>
                    <li class="no-style">price: '.$p['quantity'].' x '.$p['price'].' '. $p['currency'] .'</li>
                </ul>
            </li>';
        }

        return $html . '</ol>';
    }
}