<?php
	
	class Bookings extends Model{
		public $booking_id;
		public $employee_id;
	    public $site_id;
	    public $customer_id;
	    public $booking_date;
	    public $start_time;
	    public $end_time;
	    public $status;
	    public $message;
	    public $service;

	    public function getEmployeeBookings($employee_id, $booking_date){
	    	//return all booking for an employee on a particular day
	    	$sql = 'SELECT * FROM Bookings WHERE employee_id = :employee_id AND booking_date = :booking_date';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['employee_id'=>$employee_id, 'booking_date' => $booking_date]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetchAll();
    	}

    	public function getEmployeeSchedule($employee_id, $booking_date){
	    	//return all booking for an employee on a particular day
	    	$sql = 'SELECT * FROM Bookings WHERE employee_id = :employee_id AND booking_date = :booking_date ORDER BY start_time, end_time';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['employee_id'=>$employee_id, 'booking_date' => $booking_date]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetchAll();
    	}


    	public function getSiteSchedule($site_id, $booking_date){
	    	//return all booking for an employee on a particular day
	    	$sql = 'SELECT * FROM Bookings WHERE site_id = :site_id AND booking_date >= :booking_date ORDER BY start_time, end_time';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['site_id'=>$site_id, 'booking_date' => $booking_date]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetchAll();
    	}

    	public function getCustomerBookings($customer_id){
    		$sql = 'SELECT * FROM Bookings WHERE customer_id = :customer_id ';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['customer_id'=>$customer_id]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetchAll();
    	}

    	public function getBooking($booking_id){
    		$sql = 'SELECT * FROM Bookings WHERE booking_id = :booking_id';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['booking_id'=>$booking_id]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetch();
    	}

    	public function getEmployeesUnconfirmedBookings($employee_id, $status){
    		$sql = 'SELECT * FROM Bookings WHERE employee_id = :employee_id AND status = :status';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['employee_id'=>$employee_id, 'status'=>$status]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetchAll();
    	}



    	public function getSiteBookings($site_id, $booking_date){
	        $sql = 'SELECT * FROM Bookings WHERE site_id = :site_id AND booking_date = :booking_date ORDER BY start_time = :start_time, end_time = :end_time';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['site_id'=>$site_id, 'booking_date' => $booking_date, 'start_time' => $start_time, 'end_time' => $end_time]);
	        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Bookings');
	        return $stmt->fetchAll();
    	}

    	public function insert(){
	    	$sql = 'INSERT INTO Bookings(employee_id, site_id, customer_id, booking_date, start_time, end_time, status, message, service) VALUES(:employee_id, :site_id, :customer_id, :booking_date, :start_time, :end_time, :status, :message, :service)';
	        $stmt = self::$_connection->prepare($sql);
	        $stmt->execute(['employee_id'=>$this->employee_id,'site_id'=>$this->site_id, 'customer_id'=>$this->customer_id, 'booking_date'=>$this->booking_date, 'start_time'=>$this->start_time, 'end_time'=>$this->end_time, 'status'=>$this->status, 'message'=>$this->message, 'service'=>$this->service]);
	        return self::$_connection->lastInsertId();
    	}


    	public function update(){
            $SQL = 'UPDATE Bookings SET status = :status WHERE booking_id = :booking_id';
            $stmt = self::$_connection->prepare($SQL);
            $stmt->execute(['status'=>$this->status, 'booking_id'=>$this->booking_id]);
            return $stmt->rowCount();
        }

	}



?>