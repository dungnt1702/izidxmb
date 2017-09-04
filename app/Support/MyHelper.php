<?php // Code within app\Helpers\Helper.php
    function v_fRedirect($the_sz_Url, $the_sz_AlertMessage = '')
    {
        $sz_Html = '<script>';
        if($the_sz_AlertMessage != ''){
                $sz_Html .= 'alert("' . $the_sz_AlertMessage . '");';
        }
        $sz_Html .= 'window.location="' . $the_sz_Url . '";</script>';
        die($sz_Html);
    }
    function sz_fCurrentHost()
    {
        $sz_Protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    	return $sz_Protocol . $_SERVER['HTTP_HOST'] . '/';
    }
?>
