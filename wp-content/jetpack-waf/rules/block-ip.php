<?php
$waf_block_list = array (
  0 => '179.43.191.18',
  1 => '178.34.161.46',
  2 => '184.154.161.200',
  3 => '188.43.253.76',
  4 => '86.137.86.139',
  5 => '5.42.65.41',
  6 => '77.232.39.226',
  7 => '109.107.172.142',
  8 => '95.26.142.118',
  9 => '159.253.120.64',
  10 => '37.139.53.68',
  11 => '37.79.136.198',
  12 => '104.237.6.28',
  13 => '95.182.125.108',
  14 => '109.248.55.205',
  15 => '62.84.99.188',
);
return $waf->is_ip_in_array( $waf_block_list );
