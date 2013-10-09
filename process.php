<?php
include('functions.php');
exec('rm data.txt');
exec('rm combined.txt');
exec('rm *.graph');
exec('rm *.ps');
exec('rm *.png');

#errors 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

#server setting.
# Change according to your server path
$uploadfile = "/home/gagan/public_html/newfelt/";

#work if file has been posted for upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
  {
  	$url = !empty($_POST['stadFileURL']) ? $_POST['stadFileURL'] : '' ;
    	if($url != '') 
        {
    	    $stadfilename  = basename($url);
    	    downloadFile($url,$uploadfile);
        }
      else
        {
        	$stadfilename  = $_FILES['stadfilex']['name'];
        	$type     = $_FILES['stadfilex']['type'];
        	$tmp_name = $_FILES['stadfilex']['tmp_name'];
        	$error    = $_FILES['stadfilex']['error'];
        	$size     = $_FILES['stadfilex']['size'];
        	move_uploaded_file($_FILES['stadfilex']['tmp_name'],
                             $uploadfile.$stadfilename);
        }

    # Generates graph and analysis file
   	exec('felt -graphics graph.graph '. $stadfilename. ' > data.txt');

    # This generates the .ps files
   	exec('env /usr/bin/gnuplot 1.dem');

    # Converts .ps to .png  
   	exec('convert -rotate 90 1.ps 1.png');
   	exec('convert -rotate 90 2.ps 2.png');
   	exec('convert -rotate 90 3.ps 3.png');

    # Copies input file to combined file then further result is appended
   	exec('cp '.$stadfilename.' combined.txt');

    # Reads analysis data   
   	$comb_data = file_get_contents('data.txt');
   
    # Opens file to write combined data
   	$handle = fopen('combined.txt', 'a');
   
    # Writes the combined data
   	fwrite($handle , $comb_data);

   	#file removed for next time ;-)
   	unlink($stadfilename);

    echo '<META http-equiv="refresh" content=";URL=output.html">';
}		

#will work if data is not posted
else {
	echo "Error Uploading file";
}
