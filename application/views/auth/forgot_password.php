<p>Please enter your email address so we can send you an email to reset your password.</p>


<?php echo form_open("auth/forgot_password");?>

  <table>
      <tr><th>Email Address</th><td>
      <?php echo form_input($email);?>
      </td></tr>
      
      <tr><th>&nbsp;</th><td><?php echo form_submit('submit', 'Submit');?></td></tr>
  </table>
<?php echo form_close();?>