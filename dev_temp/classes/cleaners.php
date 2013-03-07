<?php

/* clean up an input, replace all sorts of line breaks with the supplied characted . default to a space */

function cleanlb($input, $char = ' ')
{
	for ($i = 0; $i <strlen($input); $i++)
	{
		if( ord($input{$i}) == '10' || ord($input{$i}) == '11' || ord($input{$i}) == '12' )
		{
			$output .= $char;
		}
		else
		{
			$output .= $input{$i};
		}
	}
	
	$output = str_replace("\r\n", "\n", $output);
	$output = str_replace("\r", "\n", $output);
	
	return $output;

}

function showchar($input)
{
	for ($i = 0; $i <strlen($input); $i++)
	{
	 	$output .= ord($input{$i})." ";
	}
	
	return $output;
	
}
	
?>