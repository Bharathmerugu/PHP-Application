<?php

    // This is the Product resource file. We will be defining RESTFul methods that will allow you to 
    // perform basic CRUD methods. 

    // STEP 1. include the database connection file.
        include 'dbconnect.php';

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "GET"){
            
                $sql = $db->prepare('SELECT * FROM cart');
	            $sql->execute();
                $users = $sql->fetchAll(PDO::FETCH_ASSOC);
                print (json_encode($users));

        }

        else if( $method == "POST"){
            
            // STEP 3: Get the json from post body of the request
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);
            
            // Step 4: Create Prepared Statement for adding a new User record in the database.
            $cmd = 'INSERT INTO cart(product, quantities, user)'.
            'VALUES (:product, :quantities, :user)';
            $sql = $db->prepare($cmd);

            // STEP 5: Bind the values of the post body stored in the variable $json with the database columns.
            $productname = $json['product'];
            $sql->bindValue(':product', $json['product']);
            $sql->bindValue(':quantities', $json['quantities']);
            $sql->bindValue(':user', $json['user']);
    
            // STEP 6: Execute the query.
            $sql->execute();

            // STEP 7: Send a response back to the client to inform about successful addition and the id of the created record.
            $response = array("message "=>"New product ". $productname . " added to cart.");
            print json_encode($response);
        }
     
        else if ($method == "PUT") {
            // Get the http body from the request
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);
 
 
            // construct the prepared statement based on the id provided
            $fieldlist = "";
            if($json['product']) $fieldlist .= "product=:product,";
            if($json['quantities']) $fieldlist .= "quantities=:quantities,";
            if($json['user']) $fieldlist .= "user=:user,";
           
            // trim any trailing ','s
            $fieldlist = trim($fieldlist, ",");
 

            // create update statement
            $cmd = "UPDATE cart SET $fieldlist WHERE product = :product";
            $sql = $db->prepare($cmd);
 
            // bind values
            if($json['product']) $sql->bindValue(':product', $json['product']);
 
            if($json['quantities']) $sql->bindValue(':quantities', $json['quantities']);
 
            if($json['user']) $sql->bindValue(':user', $json['user']);
 
            // execute statement
            $sql->execute();
 
            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "product in cart modified");
            print json_encode($response);
        
        }
        else if ($method == "DELETE"){
            // CHALLENGE: Delete the user 
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);

            $cmd = "DELETE from cart WHERE product = :product";
            $sql = $db->prepare($cmd);

            // bind values
            $sql->bindValue(':product', $json['product']);

            // execute statement
            $sql->execute();

            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "product deleted from cart");

            print json_encode($response);
        }
?>