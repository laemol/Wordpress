<?php
/**
 * Template Name: Include
 * The main template file for display tour incluse page
 *
 * @package WordPress
*/

?>

<!doctype html>

<html>
<head>
<title>Holli</title>

<style>   
body {
    font-family: Poppins, Helvetica, Arial, sans-serif;
}
a {
    color: #222;
    text-decoration: none;
}
h4 {
    font-size: 20px;
    font-weight: 600;
    letter-spacing: -1px;
    margin: 0px;
    
}
p {
    margin: 0 0 1.5em;
    padding: 0;
}

.card-content p {
    font-size: 13px;
}
</style>

<link rel="stylesheet" href="/wp-content/plugins/holli/assets/css/holli.css">

</head>

<body>
  <?php
    $shortcode = '[products';
    if($_GET['limit']){
        $shortcode .= ' limit=' . $_GET['limit'] . ' ';
    }
    if($_GET['recommended']){
        $shortcode .= ' recommended=' . $_GET['recommended'] . ' ';
    }
    if($_GET['button']){
        $shortcode .= ' button=' . $_GET['button'] . ' ';
    }
    if($_GET['area']){
        $shortcode .= ' area=' . $_GET['area'] . ' ';
    }
    $shortcode .= ']';
   echo do_shortcode($shortcode);
   ?>
</body>
</html>
