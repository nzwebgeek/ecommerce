 <div id="page-wrapper">
<div class="container-fluid">
<h3 class="bg-success text-center"><?php display_message(); ?></h3>
  <?php add_category(); ?>          

            

<h1 class="page-header">
  Product Categories
</h1>



<div class="col-md-4">
    
    <form action="" method="post">
    
        <div class="form-group">
            <label for="category_title">Title</label>
            <input name="cat_title" type="text" class="form-control">
        </div>

        <div class="form-group">
            
            <input type="submit" name="add_category" class="btn btn-primary" value="Add Category">
        </div>      


    </form>


</div>


<div class="col-md-8">

    <table class="table">
            <thead>

        <tr>
            <th>id</th>
            <th>Title</th>
        </tr>
            </thead>


    <tbody>
        <?php show_categories_in_admin(); ?>
    </tbody>

        </table>

</div>



                













            </div>
            <!-- /.container-fluid -->

      