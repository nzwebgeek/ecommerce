

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="business" value="edwin-facilitator@codingfaculty.com">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="upload" value="1">


    <table class="table table-striped">
        <thead>
          <tr>
           <th>Product</th>
           <th>Price</th>
           <th>Quantity</th>
           <th>Sub-total</th>

          </tr>
        </thead>
        <tbody>
          <?php cart(); ?>
        </tbody>
    </table>
</form>




