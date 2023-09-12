<?php require_once('../resources/config.php'); ?>
<!-- Navigation -->
<?php include(TEMPLATE_FRONT . DS . "header.php"); ?> 
    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header>
            <h1>Shop</h1>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Latest Products</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        
        <div class="row text-center">

        <?php get_product_shop() ?>
   
        </div>
        <!-- /.row -->


        <hr>

        <!-- Footer -->
        

    </div>
    <!-- /.container -->

    <?php include(TEMPLATE_FRONT . DS . "footer.php") ;?>