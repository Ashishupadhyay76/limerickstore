<?php
$waf_allow_list = array (
  0 => '182.69.181.80',
);
return $waf->is_ip_in_array( $waf_allow_list );
