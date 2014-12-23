<?php

	define('__ROOT__', dirname(dirname(__FILE__))); 
	require_once(__ROOT__ . "/config/db.php");
	
	class SQL
	{
		private $db_connection = null;

		public $errors = array();

	    public $messages = array();

	    function findAllEventsPast($user_id, $user_type)
	    {
	    	//connect to db
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

			//change the sql query depending on the user type (customer or employee)
			if($user_type == "customer")
	    	{
	    		$sql = "SELECT * 
						FROM login.events AS logevents
							INNER JOIN login.users AS logusers ON logevents.customer_id = logusers.user_id
							INNER JOIN login.employees AS logworkers ON logevents.employee_id = logworkers.user_id
						WHERE
							logusers.user_id = ". $user_id . 
							" AND
							date < CURDATE()
						ORDER BY date DESC LIMIT 6";
	    	}
	    	else
	    	{
				$sql = "SELECT 
							logevents.id, logevents.date, logevents.cost, logevents.description, logevents.employee_id,
							logusers.name, logusers.address1, logusers.city, logusers.address2, logusers.zip, logusers.phone, logusers.state
						FROM login.events AS logevents
							INNER JOIN login.users AS logusers ON logevents.customer_id = logusers.user_id
							INNER JOIN login.employees AS logworkers ON logevents.employee_id = logworkers.user_id
						WHERE
							logworkers.user_id = ". $user_id . 
						" AND
							date < CURDATE()
						ORDER BY date ASC LIMIT 10";
	    	}
	    	
	    	//query the db
	    	$result = mysqli_query($this->db_connection, $sql);

	    	if($result->num_rows <= 0)
		    {
		    	#echo $result->num_rows;
		    	echo "<h4>You don't have any past appointments.</h4>";
		    	#$result->close();
		    }
		    else
		    {
		    	if($user_type == "customer") {
			    	echo '<table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>#</th>
			                  <th>Date</th>
			                  <th>Address</th>
			                  <th>Cost</th>
			                  <th>Description</th>
			                  <th>Technician</th>
			                </tr>
			              </thead>
			              <tbody>';
			    	while ($row = $result->fetch_object())
	                {
	                	echo "<tr>";
	                		echo "<td>" . $row->id . "</td>";
	                		echo "<td>" . $row->date . "</td>";
	                		echo "<td>";
	                			echo "<address>";
	                				echo "<strong>" . $row->address1 . "</strong><br>";
	                				if($row->address2 != null){	
	                					echo $row->address2 . "<br>";
	                				}
	                				echo $row->city . " " . $row->state . " " . $row->zip . "<br>";
	                				echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
	                			echo "</address>";
	                		echo "</td>";
	                		echo "<td>$" . $row->cost . "</td>";
	                		echo "<td>" . $row->description . "</td>";
	                		echo "<td>" . $row->name . "</td>";
	                	echo "</tr>";
	                }
			    	echo '</tbody> 
	            	</table>';
	            	$result->close();
			    	/*
					while ($obj = $result->fetch_object()) {
				        printf ("%s (%s)\n", $obj->Name, $obj->CountryCode);
				    }
			    	*/
			    }
			    else
		    	{
					echo '<table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>#</th>
			                  <th>Date</th>
			                  <th>Customer</th>
			                  <th>Address</th>
			                  <th>Description</th>
			                </tr>
			              </thead>
			              <tbody>';
					        while ($row = $result->fetch_object())
				            {
		                	echo "<tr>";
		                		echo "<td>" . $row->id . "</td>";
		                		echo "<td>" . $row->date . "</td>";
		                		echo "<td>" . $row->name . "</td>";
		                		echo "<td>";
		                			echo "<address>";
		                				echo "<strong>" . $row->address1 . "</strong><br>";
		                				if($row->address2 != null){	
		                					echo $row->address2 . "<br>";
		                				}
		                				echo $row->city . " " . $row->state . " " . $row->zip . "<br>";
		                				echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
		                			echo "</address>";
		                		echo "</td>";
		                		echo "<td>" . $row->description . "</td>";
		                	echo "</tr>";
		                }
		              echo '</tbody>
		            </table>';
				}
		    }
	    }

	    function findNearestService($user_id, $user_type) //finds the next appointment date
	    {
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

			if($user_type == "customer")
			{
	    		$sql = "SELECT * FROM login.events WHERE events.customer_id = " . $user_id . " AND date >= CURDATE() ORDER BY date ASC LIMIT 1";
	    	}
	    	else
	    	{
	    		$sql = "SELECT * FROM login.events WHERE events.employee_id = " . $user_id . " AND date >= CURDATE() ORDER BY date ASC LIMIT 1";
	    	}
	    	$result = mysqli_query($this->db_connection, $sql);

	    	if($result->num_rows <= 0)
		    {
		    	if($user_type == "customer")
		    	{
		    		echo '<div class="alert alert-info clearfix" role="alert">It seems that you don\'t have any upcoming appointments.
		    		Would you like to set up bi-weekly appointments starting from today? 
		    		<a href="customermain.php?generate" class="btn pull-right btn-info" type="button"><span aria-hidden="true">Sign up</span><span class="sr-only">Sign up</span></a></div> ';
		    	}
		    	else
		    	{
		    		echo '<div class="alert alert-info" role="alert">You don\'t have any upcoming appointments.</div>';
		    	}
		    }
		    else
		    {
		    	while($row = mysqli_fetch_array($result))
		    	{
		    		$mysqldate = $row["date"];
		    		date_default_timezone_set('EST');
					$date = date("l, F jS Y", strtotime($mysqldate));
		    		echo "<h2 class=\"sub-header\">Your next scheduled service is <mark>" . $date . "</mark></h2>";
		    	}
		    	
		    }
	    }

	    function findAllEventsAll($user_id)
	    {
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

			$sql = "SELECT 
						logevents.id, logevents.date, logevents.cost, logevents.description, logevents.employee_id,
						logusers.name, logusers.address1, logusers.city, logusers.address2, logusers.zip, logusers.phone, logusers.state
					FROM login.events AS logevents
						INNER JOIN login.users AS logusers ON logevents.customer_id = logusers.user_id
						INNER JOIN login.employees AS logworkers ON logevents.employee_id = logworkers.user_id
					WHERE
						logworkers.user_id = ". $user_id . 
					" AND
						date >= CURDATE()
					ORDER BY date ASC";
					#echo $sql;

			$result = mysqli_query($this->db_connection, $sql);
			$row_cnt = $result->num_rows;

			if ($row_cnt > 0)
			{
				echo '<h2 class="sub-header"><a href="employeemain.php?more">Upcoming Services</a> <small>(for the next six months)</small></h2>
                <div class="table-responsive">';
				echo '<table class="table table-striped">
		              <thead>
		                <tr>
		                  <th>#</th>
		                  <th>Date</th>
		                  <th>Customer</th>
		                  <th>Address</th>
		                  <th>Description</th>
		                </tr>
		              </thead>
		              <tbody>';
				        while ($row = $result->fetch_object())
			            {
	                	echo "<tr>";
	                		echo "<td>" . $row->id . "</td>";
	                		echo "<td>" . $row->date . "</td>";
	                		echo "<td>" . $row->name . "</td>";
	                		echo "<td>";
	                			echo "<address>";
	                				echo "<strong>" . $row->address1 . "</strong><br>";
	                				if($row->address2 != null){	
	                					echo $row->address2 . "<br>";
	                				}
	                				echo $row->city . " " . $row->state . " " . $row->zip . "<br>";
	                				echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
	                			echo "</address>";
	                		echo "</td>";
	                		echo "<td>" . $row->description . "</td>";
	                	echo "</tr>";
	                }
	              echo '</tbody>
	            </table>';
	            echo '</div>';
			}
			else
			{
				echo "<h4>You don't have any upcoming appointments.</h4>";
			}
	    }

	    function findAllEventsFuture($user_id, $user_type)
	    {
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

			#$sql = "SELECT * FROM login.events WHERE ". $user_type ."_id = " . $user_id. " and date >= CURDATE() ORDER BY date ASC";

			if($user_type == "customer")
			{
				$sql = "SELECT 
							logevents.id, logevents.date, logevents.cost, logevents.description, logevents.employee_id,
							logworkers.name, logusers.address1, logusers.city, logusers.address2, logusers.zip, logusers.phone, logusers.state
						FROM login.events AS logevents
							INNER JOIN login.users AS logusers ON logevents.customer_id = logusers.user_id
							INNER JOIN login.employees AS logworkers ON logevents.employee_id = logworkers.user_id
						WHERE
							logusers.user_id = ". $user_id . 
						" AND
							date >= CURDATE()
						ORDER BY date ASC LIMIT 10";
			}
			else
			{
				$sql = "SELECT 
							logevents.id, logevents.date, logevents.cost, logevents.description, logevents.employee_id,
							logusers.name, logusers.address1, logusers.city, logusers.address2, logusers.zip, logusers.phone, logusers.state
						FROM login.events AS logevents
							INNER JOIN login.users AS logusers ON logevents.customer_id = logusers.user_id
							INNER JOIN login.employees AS logworkers ON logevents.employee_id = logworkers.user_id
						WHERE
							logworkers.user_id = ". $user_id . 
						" AND
							date BETWEEN (CURDATE()) AND (CURDATE()+7)
						ORDER BY date ASC";
						#echo $sql;
			}

			$result = mysqli_query($this->db_connection, $sql);
			$row_cnt = $result->num_rows;
			if ($row_cnt > 0)
			{
				if($user_type == "customer") {
					echo '<table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>#</th>
			                  <th>Date</th>
			                  <th>Address</th>
			                  <th>Cost</th>
			                  <th>Description</th>
			                  <th>Technician</th>
			                </tr>
			              </thead>
			              <tbody>';
	                while ($row = $result->fetch_object())
	                {
	                	echo "<tr>";
	                		echo "<td>" . $row->id . "</td>";
	                		echo "<td>" . $row->date . "</td>";
	                		echo "<td>";
	                			echo "<address>";
	                				echo "<strong>" . $row->address1 . "</strong><br>";
		            				if($row->address2 != null){	
		            					echo $row->address2 . "<br>";
		            				}
	                				echo $row->city . " " . $row->state . " " . $row->zip . "<br>";
	                				echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
	                			echo "</address>";
	                		echo "</td>";
	                		echo "<td>$" . $row->cost . "</td>";
	                		echo "<td>" . $row->description . "</td>";
	                		echo "<td>" . $row->name . "</td>";
	                	echo "</tr>";
	                }
	              echo '</tbody>
	            	</table>';
	            }
	            else
	            {
					echo '<table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>#</th>
			                  <th>Date</th>
			                  <th>Customer</th>
			                  <th>Address</th>
			                  <th>Description</th>
			                </tr>
			              </thead>
			              <tbody>';
					        while ($row = $result->fetch_object())
				            {
		                	echo "<tr>";
		                		echo "<td>" . $row->id . "</td>";
		                		echo "<td>" . $row->date . "</td>";
		                		echo "<td>" . $row->name . "</td>";
		                		echo "<td>";
		                			echo "<address>";
		                				echo "<strong>" . $row->address1 . "</strong><br>";
		                				if($row->address2 != null){	
		                					echo $row->address2 . "<br>";
		                				}
		                				echo $row->city . " " . $row->state . " " . $row->zip . "<br>";
		                				echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
		                			echo "</address>";
		                		echo "</td>";
		                		echo "<td>" . $row->description . "</td>";
		                	echo "</tr>";
		                }
		              echo '</tbody>
		            </table>';
				}
			}
			else
			{
				echo "<h4>You don't have any upcoming appointments in the next week.</h4>";
			}
	    }

	    function generateEvents($user_id)
	    {
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

			//get the date that the customer registered on
			/*$sql1 = "SELECT registration_date FROM login.users WHERE user_id = " . $user_id;

			if ($result = mysqli_query($this->db_connection, $sql1)) 
			{
				$row = $result->fetch_object();
				$registration = $row->registration_date;
				echo "Registration date: " . $registration . "<br>";
				$result->close();*/

				//return the employee user_id with the lowest amount of customers (to keep work even)
				$sql = "SELECT user_id, service_count FROM login.employees ORDER BY service_count ASC";
				if($result = mysqli_query($this->db_connection, $sql))
				{
					date_default_timezone_set('EST');
					$row = $result->fetch_object();
					$employee_id = $row->user_id;

					mysqli_free_result($result);
					for ($i=1; $i < 13; $i++) { 
						$date_array = date_parse(date("Y-m-d"));
						$event = date("Y-m-d", mktime(0, 0, 0, $date_array["month"], $date_array["day"] + ($i*14), $date_array["year"]));
						#echo $event . "<br>";
						$event_parsed = date_parse(date($event));
						if(!($event_parsed["month"] > 10 or $event_parsed["month"] < 3))
						{
							$new_event = new DateTime($event);
							$sql = "INSERT INTO login.events (employee_id, customer_id, date, cost, description) VALUES " .
							"(". $employee_id .", " . $user_id . ", STR_TO_DATE('". $new_event->format("Y-m-d") . "', '%Y-%m-%d'), 25, \"Lawn\")";

							if($result = mysqli_query($this->db_connection, $sql))
							{
								if (!mysqli_query($this->db_connection, "SET @a:='this will not work'")) {
							        printf("Error: %s\n", mysqli_error($this->db_connection));
							    }
							}
							else
							{
								echo "error inserting";
							}
							$sql_increment = "UPDATE login.employees SET service_count = service_count + 1 where user_id = " . $employee_id;
							if($result = mysqli_query($this->db_connection, $sql_increment))
							{
								if (!mysqli_query($this->db_connection, "SET @a:='this will not work'")) {
							        printf("Error: %s\n", mysqli_error($this->db_connection));
							    }
							}
						}
					}
					header("location: customermain.php");
				}
			#}
			else
			{
				printf("Error: %s\n", $this->db_connection->error);
			}
	    }

	    function returnInfo($user_id, $user_type)
	    {
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

    		$sql = "SELECT user_email, name, address1, address2, city, zip, state, phone FROM " . DB_NAME .".". $user_type . " WHERE user_id = " . $user_id;

	    	$result = mysqli_query($this->db_connection, $sql);

	    	$row_cnt = $result->num_rows;
			if ($row_cnt <= 0) {
		    	echo "There was a problem retrieving your information.";
		    }
		    else
		    {
				$row = $result->fetch_object();
		    	
		    	echo "<strong>" . $row->name . "</strong><br>";
		    	echo $row->user_email . "<br>";
		    	echo "<address>";
		    	echo $row->address1 . "<br>";
		    	if($row->address2 != null)
		    	{
		    		echo $row->address2 . "<br>";
		    	}
		    	echo $row->city . ", " . $row->state . " " . $row->zip . "<br>";
		    	echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
		    	echo "</address>";
		    }
	    }

	    function generateBill($user_id)
	    {
	    	$this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	    	if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

			//the last billing period (the last month)
			date_default_timezone_set('EST');
			$from = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
			$to = date('Y-m-t', strtotime($from));

    		$sql = "SELECT * 
					FROM login.events AS logevents
						INNER JOIN login.users AS logusers ON logevents.customer_id = logusers.user_id
						INNER JOIN login.employees AS logworkers ON logevents.employee_id = logworkers.user_id
					WHERE
						logusers.user_id = ". $user_id ."
					AND
						date BETWEEN '". $from ."' AND '". $to ."' 
					ORDER BY date ASC";
			#echo $sql;
	    	$result = mysqli_query($this->db_connection, $sql);

	    	$row_cnt = $result->num_rows;
	    	#echo $row_cnt;
	    	echo "<h2 class=\"sub-header\">Billing period from <mark>" . $from . "</mark> to <mark>" . $to . "</mark></h2>";
			if ($row_cnt <= 0) {
		    	echo "<h4>You don't have any recent billings or there was a problem retrieving your information.</h4>";
		    }
		    else
		    {		    	
		    	$cost = 0;
		    	echo '<table class="table table-striped">
			              <thead>
			                <tr>
			                  <th>#</th>
			                  <th>Date</th>
			                  <th>Address</th>
			                  <th>Cost</th>
			                  <th>Description</th>
			                  <th>Technician</th>
			                </tr>
			              </thead>
			              <tbody>';
	                while ($row = $result->fetch_object())
	                {
	                	echo "<tr>";
	                		echo "<td>" . $row->id . "</td>";
	                		echo "<td>" . $row->date . "</td>";
	                		echo "<td>";
	                			echo "<address>";
	                				echo "<strong>" . $row->address1 . "</strong><br>";
		            				if($row->address2 != null){	
		            					echo $row->address2 . "<br>";
		            				}
	                				echo $row->city . " " . $row->state . " " . $row->zip . "<br>";
	                				echo "<abbr title=\"Phone\">P:</abbr> " . $row->phone;
	                			echo "</address>";
	                		echo "</td>";
	                		echo "<td><strong>$" . $row->cost . "</strong></td>";
	                		echo "<td>" . $row->description . "</td>";
	                		echo "<td>" . $row->name . "</td>";
	                	echo "</tr>";
	                	$cost = $cost + $row->cost;
	                }
	              echo '</tbody>
	            	</table>';

	            	echo "<h3 class=\"pull-right\">Your bill for the last period: <strong><mark>$" . number_format($cost, 2) . "</mark></strong></h3>";
		    }
	    }
	}
?>