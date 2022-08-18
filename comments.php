<?php

    // This is the Product resource file. We will be defining RESTFul methods that will allow you to 
    // perform basic CRUD methods. 

    // STEP 1. include the database connection file.
        include 'dbconnect.php';

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "GET"){
            // if the GET Paramater 'id' is specified in the request, only get the specified user id.
            if(isset($_GET['product'])){
                $sql = $db->prepare('SELECT * FROM comments WHERE product = :product');
	            $sql->bindValue(':product', $_GET['product']);
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
                $sql = $db->prepare('SELECT * FROM comments');
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
            $cmd = 'INSERT INTO comments(product, user, rating, image, text)'.
            'VALUES (:product, :user, :rating, :image, :text)';
            $sql = $db->prepare($cmd);

            // STEP 5: Bind the values of the post body stored in the variable $json with the database columns.
           $username = $json['user'];
            $sql->bindValue(':product', $json['product']);
            $sql->bindValue(':user', $username);
            $sql->bindValue(':rating', $json['rating']);
            $sql->bindValue(':image', $json['image']);
            $sql->bindValue(':text', $json['text']);

            // STEP 6: Execute the query.
            $sql->execute();

            // STEP 7: Send a response back to the client to inform about successful addition and the id of the created record.
            $response = array("message "=>"New Comment from " . $username );
            print json_encode($response);
        }
     
        else if ($method == "PUT") {
            // Get the http body from the request
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);
 
 
            // construct the prepared statement based on the id provided
            $fieldlist = "";
            if($json['product']) $fieldlist .= "product=:product,";
            if($json['user']) $fieldlist .= "user=:user,";
            if($json['rating']) $fieldlist .= "rating=:rating,";
            if($json['image']) $fieldlist .= "image=:image,";
            if($json['text']) $fieldlist .= "text=:text";
            
            // trim any trailing ','s
            $fieldlist = trim($fieldlist, ",");
 

            // create update statement
            $cmd = "UPDATE comments SET $fieldlist WHERE user = :user";
            $sql = $db->prepare($cmd);
 
            // bind values
            if($json['product']) $sql->bindValue(':product', $json['product']);
 
            if($json['rating']) $sql->bindValue(':rating', $json['rating']);
 
            if($json['image']) $sql->bindValue(':image', $json['image']);
 
            if($json['text']) $sql->bindValue(':text', $json['text']);

            $sql->bindValue(':user', $json['user']);

            // execute statement
            $sql->execute();
 
            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "user comment modified");
            print json_encode($response);
        
        }
        else if ($method == "DELETE"){
            // CHALLENGE: Delete the user 
            $post = trim(file_get_contents("php://input"));
            $json = json_decode($post, true);

            $cmd = "DELETE from comments WHERE user = :user";
            $sql = $db->prepare($cmd);

            // bind values
            $sql->bindValue(':user', $json['user']);

            // execute statement
            $sql->execute();

            // Send a response back to the client to inform about successfuly  modified records.
            $response = array("message" => "user comment deleted");

            print json_encode($response);
        }
?>