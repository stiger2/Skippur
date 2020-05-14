<?php

class Customer extends Model
{
    public $customer_id;
    public $customer_name;
    public $customer_email;
    public $user_id;

    public function All(){
    	//return all records
    	$sql = 'SELECT * FROM Customers';
        $stmt = self::$_connection->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
        return $stmt->fetchAll();
    }

	function getCustomer($customer_name){
    	$sql = 'SELECT * FROM Customers WHERE customer_name LIKE :customer_name';
        $stmt = self::$_connection->prepare($sql);
        $stmt->execute(['customer_name'=>$customer_name]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
        return $stmt->fetch();
    }

    function insert(){
    	$sql = 'INSERT INTO Customers(customer_name, customer_email, user_id) VALUES(:customer_name, :customer_email, :user_id)';
        $stmt = self::$_connection->prepare($sql);
        $stmt->execute(['customer_name'=>$this->customer_name,'customer_email'=>$this->customer_email, 'user_id'=>$this->user_id]);
        return self::$_connection->lastInsertId();
    }

    function getCustomerByUserId($user_id){
    	$sql = 'SELECT * FROM Customers WHERE user_id = :user_id';
        $stmt = self::$_connection->prepare($sql);
        $stmt->execute(['user_id'=>$user_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Customer');
        return $stmt->fetch();
    }

}
?>