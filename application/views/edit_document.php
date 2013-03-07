
<script type="text/javascript">

    function populateDocTypes() {
        var dt_column = document.getElementById('dt_column').value;
        var url = '<?php echo BASE_URL_RELATIVE; ?>index.php/export/document_types/' + dt_column;
        $("#dt_id").load(url);
    }

    function toggleNewDT() {
        var dt = document.getElementById('dt_id').value;
        if (dt == '[new]') { document.getElementById('new_dt_code').style.display = ''; }
        else { document.getElementById('new_dt_code').style.display = 'none'; }
    }

</script>

<?php echo $form_start; ?>

<table width="100%">
<?php foreach ($fields as $f) { extract($f); ?>
    <tr><th class="editLot-ttl"><?= $caption; ?></th><td><?= $input; ?></td></tr>
    <tr class="spacerRow"><th></th><td></td></tr>
<?php } ?>
</table>

<?php echo $actions; ?>

<?php echo $form_end; ?>

