	
    <?php echo form_open($form_action);?>
    <table>
      <tr><th>First Name*</th><td>
      <?php echo form_input($first_name);?>
      </td></tr>
      
      <tr><th>Last Name*</th><td>
      <?php echo form_input($last_name);?>
      </td></tr>
      
      <tr><th>Company Name</th><td>
      <?php echo form_input($company);?>
      </td></tr>
      
      <tr><th>Email*</th><td>
      <?php echo form_input($email);?>
      </td></tr>
      
      <tr><th>Password*</th><td>
      <?php echo form_input($password);?>
      </td></tr>
      
      <tr><th>Confirm Password*</th><td>
      <?php echo form_input($password_confirm);?>
      </td></tr>

      <tr><th>Group</th><td><?php echo $group_id_dropdown; ?>

      <tr><th>&nbsp;</th><td>
      <?php echo form_submit(array('name'=>'submit','value'=>$button_caption)); ?>
      </td></tr>
    </table>
      
    <p>* Required Fields</p>
    <?php echo $pw_note; ?>
    <?php echo $hidden.form_close();?>

