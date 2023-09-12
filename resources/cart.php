<?php require_once('config.php'); ?>

<?php
// gets the click add from index.php, get_products fn value
if(isset($_GET['add'])){
// below code fetches the id number from add route
    $query = query("SELECT * FROM products WHERE product_id=".escape_string($_GET['add']). " ");
    confirm($query);

    while($show=fetch_array($query)){

        if($show['product_quantity'] != $_SESSION['product_'.$_GET['add']]){
            $_SESSION['product_' .$_GET['add']] +=1; // if 0 then add 1
            redirect("../public/checkout.php");

        }
        else{
            // if beyond limit
            set_message("Limited {$show['product_title']} items ".$show['product_quantity']. " "."Available");
            redirect("../public/checkout.php");
        }
    }

}

if(isset($_GET['remove'])){

    $_SESSION['product_'. $_GET['remove']]--;

    if($_SESSION['product_'.$_GET['remove']] < 1){
    
    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);

    redirect("../public/checkout.php");

    }
    else{
        redirect("../public/checkout.php");

    }
}

if(isset($_GET['delete'])){

    $_SESSION['product_'.$_GET['delete']] = '0';
    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);

    redirect("../public/checkout.php");


}

function cart(){

    $total=0;
    $item_quantity=0;
    $item_name = 1;
    $item_number = 1;
    $amount = 1;
    $quantity = 1;

// value contains the key quantity 
foreach($_SESSION as $name => $value){

    if($value > 0){
         // start of character (product_), 
    if(substr($name, 0, 8) =="product_"){

        $length = strlen(is_numeric($name) - 8);

        $id = substr($name, 8, $length);

        $qry = query("SELECT * FROM products WHERE product_id = ".escape_string($id)." ");
        confirm($qry);

        while($show=fetch_array($qry)){

            $subTotal = $show['product_price'] * $value;
            $item_quantity+=$value;
            $product_image=display_image($show['product_image']);

$displayProducts = <<<DELIMETER

<tr>
    <td>{$show['product_title']}</td><br>
    <img width='100' src='../resources/{$product_image}'/>
    <td>&#36;{$show['product_price']}</td>
    <td>{$value}</td>
    <td>&#36;{$subTotal}</td>
    <td>
        <a class='btn btn-warning' href="../resources/cart.php?remove={$show['product_id']}"><span class='glyphicon glyphicon-minus'></span></a>
        <a class='btn btn-success' href="../resources/cart.php?add={$show['product_id']}"><span class='glyphicon glyphicon-plus'></span></a>
        <a class='btn btn-danger' href="../resources/cart.php?delete={$show['product_id']}"><span class='glyphicon glyphicon-remove'></span></a>

    </td>
</tr>

<input type="hidden" name="item_name_{$item_name}" value="{$show['product_title']}">
<input type="hidden" name="item_number_{$item_number}" value="{$show['product_id']}">
<input type="hidden" name="amount_{$amount}" value="{$show['product_price']}">
<input type="hidden" name="quantity_{$quantity}" value="{$value}">


DELIMETER;

echo $displayProducts;

$item_name++;
$item_number++;
$amount++;
$quantity++;


        }
    $_SESSION['item_total']= $total += $subTotal;
    $_SESSION['item_quantity']=$item_quantity;
   

        
    }
  

    }
}


}

function show_paypal(){

    if(isset($_SESSION['item_quantity']))
    {

$paypal_button = <<<DELIMETER
<input type="image" name="upload"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
DELIMETER;

return $paypal_button;

    }

}


function process_transaction(){


    if(isset($_GET['tx'])){

        $amount = $_GET['amt'];
        $currency = $_GET['cc'];
        $transaction = $_GET['tx'];
        $status = $_GET['st'];
      


    $total=0;
    $item_quantity=0;
// value contains the key quantity 
    foreach($_SESSION as $name => $value) {

    if($value > 0){
         // start of character (product_), 
     if(substr($name, 0, 8) =="product_")
        {

        $length = strlen(is_numeric($name) - 8);
    	 $id = substr($name, 8, $length);
         $send_order=query("INSERT INTO orders (order_amount, order_transaction, order_status, order_currency) VALUES('{$amount}','{$transaction}','{$status}','{$currency}')");
        
         $last_id = last_id();
         confirm($send_order);
       

        $qry = query("SELECT * FROM products WHERE product_id = ".escape_string($id)." ");
        confirm($qry);

        while($show=fetch_array($qry)){
            $product_price = $show['product_price'];
            $product_title = $show['product_title'];
            $subTotal = $show['product_price'] * $value;
            $item_quantity +=$value;

            $insert_report=query("INSERT INTO reports (product_id, order_id, product_title, product_price, product_quantity) VALUES('{$id}','{$last_id}','{$product_title}','{$product_price}','{$value}')");

            confirm($insert_report);


        }
        $total +=$subTotal;
       echo $item_quantity;
        
    }
  

    }
 }
    }
    else{
        redirect("index.php");
    }

}
?>