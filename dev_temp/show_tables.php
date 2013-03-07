<?php

include_once 'classes/mssql_storage.php';

dbConnect();

$tables = array('lc_lots','lc_documents','lc_catalog','lc_parents','lc_doctypes','lc_relationships');
foreach ($tables as $table) {
    echo "<h2>$table</h2>";
    $query = 'select * from '.$table;
    
    $result = mssql_query($query);
    if (!$result) 
    {
    	$message = 'ERROR: ' . mssql_get_last_message();
    	return $message;
    }
    else
    {
    	$i = 0;
    	echo '<table border=1 cellpadding=8><tr>';
    	while ($i < mssql_num_fields($result))
    	{
    		$meta = mssql_fetch_field($result, $i);
    		echo '<td>' . $meta->name . '</td>';
    		$i = $i + 1;
    	}
    	echo '</tr>';
    	
    	while ( ($row = mssql_fetch_row($result))) 
    	{
    		$count = count($row);
    		$y = 0;
    		echo '<tr>';
    		while ($y < $count)
    		{
    			$c_row = current($row);
    			echo '<td>' . $c_row . '</td>';
    			next($row);
    			$y = $y + 1;
    		}
    		echo '</tr>';
    	}
    	mssql_free_result($result);
    	
    	echo '</table><hr />';
        
    }
}
dbClose();
?>