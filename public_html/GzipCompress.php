<?php

//if (substr($_SERVER['HTTP_HOST'],0,3)!='www') { header("Location:http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING']); }

// Gzip encode the contents of the output buffer.
function compress_output_option($output)
{
    // Compress the data into a new var.
    $compressed_out = gzencode($output,6);

    // Don't compress any pages less than 1000 bytes
    // as it's not worth the overhead at either side.
    if(strlen($output) >= 1000)
    {
       /* error_log('compression.php Gzipd Output'."\n"
                 .'Before compression size '
                 .strlen($output).' bytes'."\n"
                 .' After compression size '
                 .strlen($compressed_out).' bytes',3,'error.txt'); */
        // Tell the browser the content is compressed with gzip
        header("Content-Encoding: gzip");
        return $compressed_out;
    }
    else
    {
        // No compression
        //error_log('compression.php Standard Output.');
        return $output;
    }
}

// Check if the browser supports gzip encoding, HTTP_ACCEPT_ENCODING

if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))  
{

    // Start output buffering, and register compress_output() (see
    // below)
    ob_start("compress_output_option");

}

?>