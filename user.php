<?php

    // This is the Product resource file. We will be defining RESTFul methods that will allow you to 
    // perform basic CRUD methods. 

    // STEP 1. include the database connection file.
        include 'dbconnect.php';

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "GET"){
            // if the GET Paramater 'id' is specified in the request, only get the specified user id.
            if(isset($_GET['id'])){
                $sql = $db->prepare('SELECT * FROM Users WHERE id = :id');
	            $sql->bindValue(':id', $_GET['id']);
	            $sql->execute();
	
	            if($user = $sql->fetch(PDO::FETCH_ASSOC)){
                    $response = json_encode($user);
                }
	            else
                    $response = array("message" => "User not found");
                    print json_encode($response);

            }

            // otherwise, get all users from the database.
            else {
                $sql = $db->prepare('SELECT * FROM Users');
	            $sql->execute();
                $users = $sql->fetchAll(PDO::FETCH_ASSOC);
                print (json_encode($users));

            }
        }

        else if( $method == "POST"){
            
            // STEP 3: Get the json from post body of the request
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);
            
            // Step 4: Create Prepared Statement for adding a new User record in the database.
            $cmd = 'INSERT INTO users(firstname,lastname , email, password, role, shippingaddress)'.
            'VALUES (:firstname, :lastname, :email, :password, :role, :shippingaddress)';
            $sql = $db->prepare($cmd);

            // STEP 5: Bind the values of the post body stored in the variable $json with the database columns.
            $pwdHash = password_hash($json['password'], PASSWORD_DEFAULT);
            $sql->bindValue(':firstname', $json['firstname']);
            $sql->bindValue(':lastname', $json['lastname']);
            $sql->bindValue(':email', $json['email']);
            $sql->bindValue(':password', $pwdHash);
            $sql->bindValue(':role', $json['role']);
            $sql->bindValue(':shippingaddress', $json['shippingaddress']);

            // STEP 6: Execute the query.
            $sql->execute();

            // STEP 7: Send a response back to the client to inform about successful addition and the id of the created record.
            $response = array("message "=>"New user with id ". $db->lastInsertId() . " added");
            print json_encode($response);
        }
     
        else if ($method == "PUT") {
            // Get the http body from the request
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);
 
 
            // construct the prepared statement based on the id provided
            $fieldlist = "";
            if($json['firstname']) $fieldlist .= "firstname=:firstname,";
            if($json['lastname']) $fieldlist .= "lastname=:lastname,";
            if($json['email']) $fieldlist .= "email=:email,";
            if($json['password']) $fieldlist .= "password=:password,";
            if($json['role']) $fieldlist .= "role=:role,";
            if($json['shippingaddress']) $fieldlist .= "shippingaddress=:shippingaddress";
 
            // trim any trailing ','s
            $fieldlist = trim($fieldlist, ",");
 

            // create update statement
            $cmd = "UPDATE Users SET $fieldlist WHERE id = :id";
            $sql = $db->prepare($cmd);
 
            $pwdHash = password_hash($json['password'], PASSWORD_DEFAULT);
            // bind values
            if($json['firstname']) $sql->bindValue(':firstname', $json['firstname']);
 
            if($json['lastname']) $sql->bindValue(':lastname', $json['lastname']);
 
            if($json['email']) $sql->bindValue(':email', $json['email']);
 
            if($json['password']) $sql->bindValue(':password', $pwdHash);
 
            if($json['role']) $sql->bindValue(':role', $json['role']);
            
            if($json['shippingaddress']) $sql->bindValue(':shippingaddress', $json['shippingaddress']);

            $sql->bindValue(':id', $json['id']);

            // execute statement
            $sql->execute();
 
            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "user record modified");
            print json_encode($response);
        
        }
        else if ($method == "DELETE"){
            // CHALLENGE: Delete the user 
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);

            $cmd = "DELETE from Users WHERE id = :id";
            $sql = $db->prepare($cmd);

            // bind values
            $sql->bindValue(':id', $json['id']);

            // execute statement
            $sql->execute();

            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "user record deleted");

            print json_encode($response);
        }
?>