<?php

    // This is the Product resource file. We will be defining RESTFul methods that will allow you to 
    // perform basic CRUD methods. 

    // STEP 1. include the database connection file.
        include 'dbconnect.php';

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "GET"){
            // if the GET Paramater 'id' is specified in the request, only get the specified user id.
            if(isset($_GET['productname'])){
                $sql = $db->prepare('SELECT * FROM product WHERE productname = :productname');
	            $sql->bindValue(':productname', $_GET['productname']);
	            $sql->execute();
	
	            if($user = $sql->fetch(PDO::FETCH_ASSOC)){
                    $response = json_encode($user);
                }
	            else
                    $response = array("message"=>"Product not found");
                    print json_encode($response);

            }

            // otherwise, get all users from the database.
            else {
                $sql = $db->prepare('SELECT * FROM product');
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
            $cmd = 'INSERT INTO product(productname, description,image , pricing, shippingcost)'.
            'VALUES (:productname, :description, :image, :pricing, :shippingcost)';
            $sql = $db->prepare($cmd);

            // STEP 5: Bind the values of the post body stored in the variable $json with the database columns.
            $productname = $json['productname'];
            $sql->bindValue(':productname', $productname);
            $sql->bindValue(':description', $json['description']);
            $sql->bindValue(':image', $json['image']);
            $sql->bindValue(':pricing', $json['pricing']);
            $sql->bindValue(':shippingcost', $json['shippingcost']);
    
            // STEP 6: Execute the query.
            $sql->execute();

            // STEP 7: Send a response back to the client to inform about successful addition and the id of the created record.
            $response = array("message "=>"New product ". $productname . " added");
            print json_encode($response);
        }
     
        else if ($method == "PUT") {
            // Get the http body from the request
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);
 
 
            // construct the prepared statement based on the id provided
            $fieldlist = "";
            if($json['productname']) $fieldlist .= "productname=:productname,";
            if($json['description']) $fieldlist .= "description=:description,";
            if($json['image']) $fieldlist .= "image=:image,";
            if($json['pricing']) $fieldlist .= "pricing=:pricing,";
            if($json['shippingcost']) $fieldlist .= "shippingcost=:shippingcost,";

            // trim any trailing ','s
            $fieldlist = trim($fieldlist, ",");
 

            // create update statement
            $cmd = "UPDATE product SET $fieldlist WHERE productname = :productname";
            $sql = $db->prepare($cmd);
 
            // bind values
            if($json['productname']) $sql->bindValue(':productname', $json['productname']);
 
            if($json['description']) $sql->bindValue(':description', $json['description']);
 
            if($json['image']) $sql->bindValue(':image', $json['image']);
 
            if($json['pricing']) $sql->bindValue(':pricing', $json['pricing']);
            
            if($json['shippingcost']) $sql->bindValue(':shippingcost', $json['shippingcost']);

            // execute statement
            $sql->execute();
 
            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "product record modified");
            print json_encode($response);
        
        }
        else if ($method == "DELETE"){
            // CHALLENGE: Delete the user 
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);

            $cmd = "DELETE from product WHERE productname = :productname";
            $sql = $db->prepare($cmd);

            // bind values
            $sql->bindValue(':productname', $json['productname']);

            // execute statement
            $sql->execute();

            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "product record deleted");

            print json_encode($response);
        }
?>