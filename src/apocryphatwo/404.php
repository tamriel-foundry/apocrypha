<?php

/* For convenience, I can use the 404 page as a testing ground for stuff */
?>

This is the test 404 page.<br>


<pre>
<?php 

global $wp_query;
print_r( $wp_query ); 
?>
</pre>