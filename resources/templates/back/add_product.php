<div class="col-md-12">

<div class="row">
<h1 class="page-header">
   Add Product
   <?php add_product(); ?>

</h1>
</div>
               


<form action="" method="post" enctype="multipart/form-data">

<div class="col-md-8">

<div class="form-group">
    <label for="product-title">Product Title </label>
        <input type="text" name="product_title" class="form-control" required>
       
    </div>


    <div class="form-group">
           <label for="product-title">Product Description</label>
      <textarea name="product_description" id="" cols="30" rows="10" class="form-control" required></textarea>
    </div>



    <div class="form-group row">

      <div class="col-xs-3">
        <label for="product-price">Product Price</label>
        <input type="number" name="product_price" class="form-control" size="60" required>
      </div>
    </div>


    <div class="form-group">
           <label for="short_desc">Product Short Description</label>
      <textarea name="short_desc" id="" cols="30" rows="3" class="form-control" required></textarea>
    </div>


    
    

</div><!--Main Content-->


<!-- SIDEBAR-->


<aside id="admin_sidebar" class="col-md-4">

     
     <div class="form-group">
       <input type="submit" name="draft" class="btn btn-warning btn-lg" value="Draft">
        <input type="submit" name="publish" class="btn btn-primary btn-lg" value="Publish">
    </div>


     <!-- Product Categories-->

    <div class="form-group">
         <label for="product_category_id">Product Category</label>
        <select name="product_category_id" id="" class="form-control" required>
            <option value="">Select Category</option>
      <?php show_categories_add_product_page(); ?>
           
        </select>


</div>





    <!-- Product Brands-->


    <div class="form-group">
      <label for="product_quantity">Product Quantity</label>
         <input type="number" class="form-control" name="product_quantity" required>
    </div>


<!-- Product Tags -->


 <!--   <div class="form-group">
          <label for="product-title">Product Keywords</label>
          <hr>
        <input type="text" name="product_tags" class="form-control">
    </div>-->

    <!-- Product Image -->
    <div class="form-group">
        <label for="product_image">Product Image</label>
        <input type="file" name="file" required>
      
    </div>



</aside><!--SIDEBAR-->


    
</form>
