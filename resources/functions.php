<?php
$uploads_directory = "uploads";
// helper fns

function last_id(){
    global $connection;

    return mysqli_insert_id($connection);

}

function set_message($msg){

    if(!empty($msg)){

        $_SESSION['message']=$msg;
    }
    else{
        $msg="";
    }
}

function display_message(){

    if(isset($_SESSION['message'])){

        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location){
    header("Location: $location ");
}


function query($sql){
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result){
    global $connection;
    if(!$result){
        die("Query Failed ".mysqli_error($connection));
    }
}

function escape_string($string){

    global $connection;

    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result){
    return mysqli_fetch_array($result);
}

/**********front end functions*****/

// get products

function get_products(){
 $sqlQry= query('SELECT * FROM products WHERE product_quantity >=1');
 confirm($sqlQry);

while($result=fetch_array($sqlQry)){
    
$product = <<<DELIMETER
<div class="col-sm-4 col-lg-4 col-md-4">
        <div class="thumbnail">
            <a href="item.php?id={$result['product_id']}"><img src="../resources/uploads/{$result['product_image']}" alt=""></a>
            <div class="caption">
                <h4 class="pull-right">&#36;{$result['product_price']}</h4>
                <h4><a href="item.php?id={$result['product_id']}">{$result['product_title']}</a>
                </h4>
                <p>This is a short description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$result['product_id']}">Add To Cart</a>
            </div>
        </div>
    </div>

DELIMETER;

echo $product;
}
}
// get product category
function get_product_category(){
$sqlQry= query("SELECT * FROM products WHERE product_category_id=".escape_string($_GET['id'])." AND product_quantity >=1 ");
confirm($sqlQry);

while($final=fetch_array($sqlQry)){
//$product_image=display_image($final['product_image']);
$product = <<<DELIMETER

        <div class="col-md-3 col-sm-6 hero-feature">
            <div class="thumbnail">
            <img width='100' src="../resources/uploads/{$final['product_image']}" alt="nope no pic">
                <div class="caption">
                    <h3>{$final['product_title']}</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                    <p>
                    <!--    
                    <a href="item.php?id={$final['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
                    -->
                    <a href="../resources/cart.php?add={$final['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
                    </p>
                </div>
            </div>
        </div>
   
DELIMETER;

echo $product;
}
}
// shop products col-md-3
function get_product_shop(){
    $sqlQry= query("SELECT * FROM products WHERE product_quantity >=1");
    confirm($sqlQry);
    
    while($final=fetch_array($sqlQry)){
    $product_image=display_image($final['product_image']);
    $product = <<<DELIMETER
    
            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="No Image">
                    <div class="caption">
                        <h3>{$final['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                        <!--
                        <a href="item.php?id={$final['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
                        -->
                        <a href="../resources/cart.php?add={$final['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="#" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>
       
    DELIMETER;
    
    echo $product;
    }
    }

 function get_categories(){

$query=query("SELECT * FROM categories");
confirm($query);

while($row=fetch_array($query)){

$category_links = <<<DELIMETER

<a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>;

DELIMETER;
 echo $category_links;
        
    }
 }

 function login_user(){

     if(isset($_POST['submit'])){

        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);

        $qry=query("SELECT * FROM users WHERE username='{$username}'AND password = '{$password}'");
        confirm($qry);

        if(mysqli_num_rows($qry) == 0){

            set_message('Your Input Is Invalid');

            redirect("login.php");
        }
        else{
            $_SESSION['username'] = $username;
            redirect("admin");
        }

     }
 }

 function send_message(){

     if(isset($_POST['submit'])){

        $to = 'fabricflannigan@gmail.com';
        $from_name = $_POST['name'];
        $subject = $_POST['subject'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $headers = "From: {$from_name}{$email}";

      $results =  mail($to, $subject, $message, $headers);

      if(!$results){
        set_message('error');
        redirect("contact.php");
      }
      else{
          set_message('sent');
      }        
     }
 }
 /************back end functions***********/

function display_orders(){

$query = query("SELECT * FROM orders");
confirm($query);

while($row = fetch_array($query)){

$orders= <<<DELIMETER

<tr>
    <td>{$row['order_id']}</td>
    <td>{$row['order_amount']}</td>
    <td>{$row['order_transaction']}</td>
    <td>{$row['order_currency']}</td>
    <td>{$row['order_status']}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_order.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;
 echo $orders;
     }
 }

/*************admin products page********/
function display_image($picture){
    global $uploads_directory;
    return $uploads_directory . DS . $picture;
}

function get_products_in_admin(){

    $sqlQry= query("SELECT * FROM products");
    confirm($sqlQry);
   
while($result=fetch_array($sqlQry)){

$category =  show_product_category_title($result['product_category_id']);

$product_image= display_image($result['product_image']);

$product = <<<DELIMETER
   <tr>
        <td>{$result['product_id']}</td>
        <td>{$result['product_title']}<br>
            <a href="index.php?edit_product&id={$result['product_id']}">
            <img width='100' src="../../resources/{$product_image}" alt="edit pic"></a>
            
            
        </td>
        <td>{$category}</td>
        <td>&dollar;{$result['product_price']}</td>
        <td>{$result['product_quantity']}</td>
        <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$result['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>


    </tr>
      
   
DELIMETER;
   
   echo $product;
   }
}

// Admin Products ---- show product category start

function show_product_category_title($product_category_id){

$cat_query = query("SELECT * FROM categories WHERE cat_id='{$product_category_id}'");
confirm($cat_query);

while($cat_row = fetch_array($cat_query)){
return $cat_row['cat_title'];
}

}

// Admin Products ---- show product category end

function add_product(){
    
    if(isset($_POST['publish'])){
        
    $product_title = escape_string($_POST['product_title']);
    $product_category_id = escape_string($_POST['product_category_id']);
    $product_price = escape_string($_POST['product_price']);
    $product_quantity = escape_string($_POST['product_quantity']);
    $product_description = escape_string( $_POST['product_description']);
    $short_desc = escape_string($_POST['short_desc']);
    $product_image = $_FILES['file']['name'];// name
    $image_temp_location = $_FILES['file']['tmp_name']; //url

    move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);
    
    $query=query("INSERT INTO products(product_title, product_category_id, product_price, product_quantity, product_description, short_desc, product_image) VALUES('{$product_title}',{$product_category_id},'{$product_price}','{$product_quantity}','{$product_description}','{$short_desc}','{$product_image}') ");
    $last_inLine= last_id();
    confirm($query);
    set_message("New Product Added {$last_inLine}");
    redirect("index.php?products");
    }
}


function show_categories_add_product_page(){

    $query=query("SELECT * FROM categories");
    confirm($query);
    
    while($row=fetch_array($query)){
    
$category_options = <<<DELIMETER
    
    <option value="{$row['cat_id']}">{$row['cat_title']}</option>
    
DELIMETER;
     echo $category_options;
            
        }
     }

//**********************Update Product*************************/
function update_product(){
    
    if(isset($_POST['update'])){
        
    $product_title = escape_string($_POST['product_title']);
    $product_category_id = escape_string($_POST['product_category_id']);
    $product_price = escape_string($_POST['product_price']);
    $product_quantity = escape_string($_POST['product_quantity']);
    $product_description = escape_string( $_POST['product_description']);
    $short_desc = escape_string($_POST['short_desc']);
    $product_image = $_FILES['file']['name'];// name
    $image_temp_location = $_FILES['product_image']['tmp_name']; //url

    if(empty($product_image)){
        $get_pic = query("SELECT product_image FROM products WHERE product_id =".escape_string($_GET['id'])."");
        confirm($get_pic);

        while($pic = fetch_array($get_pic)){
                $product_image=$pic['product_image'];
        }
    }

    move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);
    
    $query ="UPDATE products SET ";
    $query.= "product_title         = '{$product_title}'            ,";
    $query.= "product_category_id   = '{$product_category_id}'      ,";
    $query.= "product_price         = '{$product_price}'            ,";
    $query.= "product_quantity      = '{$product_quantity}'         ,";
    $query.= "product_description   = '{$product_description}'      ,";
    $query.= "short_desc            = '{$short_desc}'               ,";
    $query.= "product_image         = '{$product_image}'              ";
    $query.="WHERE product_id=" . escape_string($_GET['id']);

    
    $send_update_query=query($query);
    confirm($send_update_query);

    set_message("Product Updated");
    redirect("index.php?products");
    }
}
 /***************Categories in admin*****************/

 function show_categories_in_admin(){

     $category_query = query("SELECT * FROM categories");
     confirm($category_query);

     while($row = fetch_array($category_query)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

$category = <<<DELIMETER

        <tr>
            <td>{$cat_id}</td>
            <td>{$cat_title}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

        </tr>

DELIMETER;

echo $category;
;

     }
 }

 function add_category(){

     if(isset($_POST['add_category'])){
        $cat_title = escape_string($_POST['cat_title']);
        if(empty($cat_title)){
            set_message("<p class='bg-danger text-center'>".'Cannot be empty'."</p>");
        }
        else{
            $insert_cat = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
         confirm($insert_cat);
         set_message("Category Added");

         redirect("index.php?categories");

        }

         
     }

 }

 /***************Admin Users******************/

 function display_users(){

    $category_query = query("SELECT * FROM users");
    confirm($category_query);

    while($row = fetch_array($category_query)){
       $user_id = $row['user_id'];
       $username = $row['username'];
       $email = $row['email'];
       $password = $row['password'];

$user = <<<DELIMETER

       <tr>
           <td>{$user_id}</td>
           <td>{$username}</td>
           <td>{$email}</td>
           <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>

       </tr>

DELIMETER;

echo $user;
;

    }
}

function add_user(){

    if(isset($_POST['add_user'])){

        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);
       // $user_photo = extension_loaded($_FILES['file']['name']);
        //$photo_tmp = extension_loaded($_FILES['file']['tmp']);

//move_uploaded_file($photo_tmp, UPLOAD_DIRECTORY . DS . $user_photo);

        $query = query("INSERT INTO users(username, email, password) VALUES('{$username}', '{$email}', '{$password}')");
        confirm($query);

        set_message('User Added');
        redirect('index.php?users');
    }
}

function get_reports(){

    $sqlQry= query("SELECT * FROM reports");
    confirm($sqlQry);
   
while($result=fetch_array($sqlQry)){

$reports = <<<DELIMETER
   <tr>
        <td>{$result['report_id']}</td>
        <td>{$result['product_id']}</td>
        <td>{$result['order_id']}</td>
        <td>{$result['product_price']}</td>
        <td>{$result['product_title']}</td>
        <td>{$result['product_quantity']}</td>
        <td><a class="btn btn-danger" href="../../resources/templates/back/delete_reports.php?id={$result['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>


    </tr>
      
   
DELIMETER;
   
   echo $reports;
   }
}

?>