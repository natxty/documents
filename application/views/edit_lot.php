<script type="text/javascript" src="<?php echo BASE_URL_RELATIVE; ?>scripts/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL_RELATIVE; ?>scripts/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript">
// Dialog
function setDialog() {
    var url = '<?php echo BASE_URL_RELATIVE; ?>scripts/ajax/edit_docs.php';
    var data = 'lot_id=<?php echo intval($lot_id); ?>';
    $('#dialog').load(url,data).dialog({
        autoOpen: false,
        width: 800,
        position: [200,100],
        buttons: {
            "Cancel": function() { 
                $(this).dialog("destroy"); 
                $(this).dialog("close");
                setDialog();
            }
        }
    });
}

$(function(){
    setDialog();
    // Dialog Link
    $('#dialog_link').click(function(){
        $('#dialog').dialog('open');
        return false;
    });

    //add catalog
    $('#add_cat').dialog({
        autoOpen: false,
        width: 600,
        buttons: {
            "Cancel": function() { 
                $(this).dialog("close"); 
            }
        }
    });        


    // Action Buttons
    $('#launch_add_cat').click(function(){
        $('#add_cat').dialog('open');
        return false;
    });

    $('#finish_add_cat').click(function(){
        var new_cat_no = document.getElementById('new_cat_no_field').value;
        if (!new_cat_no) { alert('No catalog number was entered!'); return false; }
        document.getElementById('new_cat_no_hidden').value = new_cat_no;
        document.getElementById('cat_holder').innerHTML = '<strong>' + new_cat_no + '</strong>';
        $('#add_cat').dialog('close');
        return false;
    });

    $('#launch_preview').click(function(){
        var url = '<?php echo BASE_URL_RELATIVE; ?>index.php/view/preview/<?= @$lcp_id; ?>';
        window.open(url,'new_popup','height=800,width=1000,scrollbars=1');   
        return false;
    });

    $('#submit_form').click(function(){
        document.forms['edit_lot_form'].submit();
        return false;
    });

    $('#reset_button').click(function(){
        window.location.href = "<?= $reset_url; ?>";
        return false;
    });

    $('#finalize_changes').click(function(){
        document.getElementById('finalize').value = 'y';
        document.forms['edit_lot_form'].submit();
        return false;
    });
});
</script>

<?php echo $form_start; ?>

<h3>Main Data</h3>
<table width="90%">
<?php foreach ($fields as $f) { extract($f); ?>
    <tr><th class="editLot-ttl"><?= $caption; ?></th><td class="editLot"><?= $input; ?></td></tr>
    <tr class="spacerRow"><th></th><td></td></tr>
<?php } ?>
</table>
<?php echo $main_action; ?>

<?php if ($lot_id) { ?>

<h3>Documents</h3>
    
    <?php echo $documents; ?>
    
    <div id="dialog" title="Add a Document"><a name=\"dialog_top\"></a>
        <p>Loading...</p>
    </div>
    <?php /*<div id="preview" title="Preview">
        <p>Loading preview...</p>
    </div>*/ ?>

<?php } /*end if $lot_id*/ ?>

<div id="add_cat" title="Add Catalog Number">
    <p>New catalog number:<br /><input type="text" name="new_cat_no_field" id="new_cat_no_field" value="" size="20" maxlength="50" /></p>
    <p><a href="#" id="finish_add_cat" class="ui-state-default ui-corner-all lifecodes-button"><span class="ui-icon ui-icon-newwin"></span>Add Catalog Number</a></p>
</div>


<?php if ($actions) { ?>
<h3>Actions</h3>
<?php echo $actions; } ?>

<input type="hidden" name="new_cat_no" id="new_cat_no_hidden" value="" />
<input type="hidden" name="finalize" id="finalize" value="n" />
<?php echo $form_end; ?>
