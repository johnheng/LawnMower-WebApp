<?php
require_once("/config/db.php");

/**
 * Class registration
 * handles the user registration
 */
class Registration
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection = null;
    /**
     * @var array $errors Collection of error messages
     */
    public $errors = array();
    /**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$registration = new Registration();"
     */
    public function __construct()
    {
        if (isset($_POST["register_customer"])) {
            $this->registerNewUser("customer");
            $_SESSION["registration_type"] = "customer";
        }
        else
        {
            $this->registerNewUser("employee");
            $_SESSION["registration_type"] = "employee";
        }
    }

    /**
     * handles the entire registration process. checks all error possibilities
     * and creates a new user in the database if everything is fine
     */
    private function registerNewUser($type)
    {
        if (empty($_POST['user_name'])) {
            if(isset($_POST['user_name'])) {
            $this->errors[] = "Empty Username";
            }   
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->errors[] = "Empty Password";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->errors[] = "Password and password repeat are not the same";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->errors[] = "Password has a minimum length of 6 characters";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (empty($_POST['user_email'])) {
            $this->errors[] = "Email cannot be empty";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->errors[] = "Email cannot be longer than 64 characters";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Your email address is not in a valid email format";
        } elseif (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            // create a database connection
            $this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

            if ($this->db_connection) {
                // escaping, additionally removing everything that could be (html/javascript-) code
                $user_name = strip_tags($_POST['user_name'], ENT_QUOTES);
                $user_email = strip_tags($_POST['user_email'], ENT_QUOTES);

                $user_password = $_POST['user_password_new'];

                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                if($type == "customer")
                {
                    $sql = "SELECT * FROM ". DB_NAME . ".users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                    #$query_check_user_name = mysql_query($sql);
                    $query_check_user_name = mysqli_query($this->db_connection, $sql);
                    #$rows = $query_check_user_name->num_rows;
                    #$result = mysql_query($sql);
                    #mysql_num_rows($sql)>=1
                    if ($query_check_user_name->num_rows >= 1) 
                    {
                        $this->errors[] = "Sorry, that username / email address is already taken.";
                    } 
                    else 
                    {
                        if(!isset($_POST["address2"]))
                        {
                            $_POST["address2"] = null;
                        }
                        // write new user's data into database
                        $sql = "INSERT INTO " . DB_NAME . ".users (user_name, user_password_hash, user_email, registration_date, address1, address2, city, state, zip, phone, name)
                                VALUES('" . $user_name . "', '" 
                                    . $user_password_hash . "', '" 
                                    . $user_email 
                                    . "', NOW(),"
                                    . "'". $_POST["address1"] . "',"
                                    . "'". $_POST["address2"] . "',"
                                    . "'". $_POST["city"] . "',"
                                    . "'". $_POST["state"] . "',"
                                    . "'". $_POST["zip"] . "',"
                                    . "'". $_POST["phone"] . "',"
                                    . "'". $_POST["name"] . "'"
                                    .");";
                        $query_new_user_insert = mysqli_query($this->db_connection, $sql);

                        // if user has been added successfully
                        if ($query_new_user_insert) 
                        {
                            #$this->messages[] = "Your account has been created successfully. You can now log in.";
                            $_SESSION["message"] = "Your account has been created successfully. You can now log in.";
                            header("location: index.php");
                        } 
                        else 
                        {
                            $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                        }
                    }
                }
                    
                else
                {
                    $sql = "SELECT * FROM ". DB_NAME . ".employees WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                    #$query_check_user_name = mysql_query($sql);
                    $query_check_user_name = mysqli_query($this->db_connection, $sql);
                    #$rows = $query_check_user_name->num_rows;
                    #$result = mysql_query($sql);
                    #mysql_num_rows($sql)>=1
                    if ($query_check_user_name->num_rows >= 1) 
                    {
                        $this->errors[] = "Sorry, that username / email address is already taken.";
                    } 
                    else 
                    {
                        // write new user's data into database
                        $sql = "INSERT INTO " . DB_NAME . ".employees (user_name, user_password_hash, user_email, name)
                                VALUES('" . $user_name . "', '" 
                                    . $user_password_hash . "', '" 
                                    . $user_email . "', '"
                                    . $_POST['name'] . "'"
                                    .");";
                        $query_new_user_insert = mysqli_query($this->db_connection, $sql);

                        // if user has been added successfully
                        if ($query_new_user_insert) 
                        {
                            #$this->messages[] = "Your account has been created successfully. You can now log in.";
                            $_SESSION["message"] = "Your account has been created successfully. You can now log in.";
                            header("location: index.php");
                        } 
                        else 
                        {
                            $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                        }
                    }
                }
            } 
            else 
            {
                $this->errors[] = "Sorry, no database connection.";
            }
        } 
        else 
        {
            $this->errors[] = "An unknown error occurred.";
        }
    }
}
