<?php
$base_url_relative = '/padmin/';
$uploads_dir = '/padmin/uploads/';


$lot_id = intval($_GET['lot_id']);

?>

<script type="text/javascript" src="<?= $base_url_relative ?>scripts/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?= $base_url_relative ?>scripts/uploadify/jquery.uploadify.v2.1.4.min.js"></script>

<script type="text/javascript">

    $(function(){
        // Tabs
        $('#tabs').tabs();
        var url = '<?= $base_url_relative ?>/index.php/view/files/popup';
        $('#tabs-2').load(url);
    });


    $(document).ready(function() {
      $('#file_upload').uploadify({
        'uploader'  : '<?= $base_url_relative ?>scripts/uploadify/uploadify.swf',
        'script'    : '<?= $base_url_relative ?>scripts/uploadify/uploadify.php',
        'cancelImg' : '<?= $base_url_relative ?>scripts/uploadify/cancel.png',
        'folder'    : '<?= $uploads_dir ?>',
        'auto'      : true,
        'onError'   : function (event, ID, fileObj, errorObj) { alert(errorObj.type + ' Error: ' + errorObj.info); },
        'onComplete': function (event, queueID, fileObj, response, data) {
                          if (response !== '1') { alert(response); }
                          else { 
                            //cleanup filename (this also happens within uploadify.php)
                            var url = '<?= $base_url_relative ?>scripts/ajax/cleanup_filename.php' //note: this is correct (not cleanup_filename_helper.php)
                            var data = 'filename=' + fileObj.name;
                            $.post(url,data,function(data) {
                                var new_filename = data;

                                //load the edit document form in tab 3
                                var url = '<?= $base_url_relative ?>index.php/edit/document/new/popup/' + new_filename + '/0/<?php echo $lot_id; ?>';
                                $('#tabs-3').load(url);
    
                                //switch to tab 3
                                document.getElementById('tabs-3-tab').style.display = '';
                                var $tabs = $('#tabs').tabs(); // first tab selected
                                $tabs.tabs('select', 2); // switch to third tab

                            } );

                          }
                      }
      });
    });

    function selectFile(file_id,file_name) {
        //load the edit document form in tab 3
        var url = '<?= $base_url_relative ?>index.php/edit/document/new/popup/' + file_name + '/' + file_id + '/<?php echo $lot_id; ?>';
        $('#tabs-3').load(url);

        //switch to tab 3
        document.getElementById('tabs-3-tab').style.display = '';
        var $tabs = $('#tabs').tabs(); // first tab selected
        $tabs.tabs('select', 2); // switch to third tab
    }

    function navigate_library(new_start) {
        var url = '<?= $base_url_relative ?>index.php/view/files/popup/' + new_start;
        $('#tabs-2').load(url);
    }

    function start_search() {
        var url = '<?= $base_url_relative ?>index.php/view/files/popup';
        var data = 'search_query=' + document.getElementById('search_query').value;
        $('#tabs-2').load(url,data);
    }

</script>
<div id="tabs">
<ul>
<li><a href="#tabs-1">Import From Computer</a></li>
<li><a href="#tabs-2">Document Library</a></li>
<li id="tabs-3-tab" style="display:none"><a href="#tabs-3">Edit Document Info</a></li>
</ul>
<div id="tabs-1">
    <p>Please choose a file from your computer to upload.</p>
    <p><input id="file_upload" name="file_upload" type="file" /></p>
    <?php /*<p><a href="javascript:$('#file_upload').uploadifyUpload();">Upload File</a></p>*/ ?>
</div>
<div id="tabs-2">
    <p>Loading...</p>
</div>
<div id="tabs-3">
    <p>Loading...</p>
</div>

</div>