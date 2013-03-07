<?php
error_reporting(0);
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/*Note: this file has been significantly modified for Lifecodes by rjr.*/

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';

    //clean weird characters, etc. out of the filename
    require_once '../../application/helpers/cleanup_filename_helper.php';
    $new_filename = cleanup_filename($_FILES['Filedata']['name']);
    if (!$new_filename) { die('Error: New filename could not be generated.'); }

    $targetFile =  str_replace('//','/',$targetPath).$new_filename;
	
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);
	
	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);
		
		$success = move_uploaded_file($tempFile,$targetFile);
        $error = false;
        if (!$success) { $error = "Could not move uploaded file."; }
        else {
            switch ($_FILES['Filedata']['error']) {
                case 0:
                    $error = false;
                    break;
                case 1:
                    $error = "The file is bigger than this PHP installation allows.";
                    break;
                case 2:
                    $error = "The file is bigger than this form allows.";
                    break;
                case 3:
                    $error = "Only part of the file was uploaded.";
                    break;
                case 4:
                    $error = "No file was uploaded.";
                    break;
                case 6:
                    $error = "Missing a temporary folder.";
                    break;
                case 7:
                    $error = "Failed to write file to disk.";
                    break;
                case 8:
                    $error = "File upload stopped by extension.";
                    break;
                default:
                    $error = "Unknown error: ".$_FILES['Filedata']['error'].".";
                    break;
            }
        }
        if ($error) { echo "Error: ".$error; }
        else { echo "1"; } // This is required for onComplete to fire on Mac OSX

		//echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>