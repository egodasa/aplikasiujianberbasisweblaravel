<?php
$urls = getenv('DATABASE_URL');
var_dump(parse_url($urls));
echo "<br/>";
echo "<br/>";
$url = 'postgres://blwbpohkelzrhc:4ee8ba4221114683c256830fc6cd3973adb953935cd8d51abf24529094df7947@ec2-54-163-249-237.compute-1.amazonaws.com:5432/d4mduqmtfgv1a3';
var_dump(parse_url($url));
?>
