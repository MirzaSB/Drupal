<?php

/*
Find out what array functions we tried that you can use on objects as well.
*/

// 1. The object equivalent of an associative array.

//The answer is none. count doesnt work properly. It always displays 1 even when there are multiple objects.

$my_object = new stdClass;

$my_object->George = 1972;
$my_object->Sally = 1975;
$my_object->Deepak = 1869;

// 2. The object equivalent of a multi-dimensional array.
$my_object = new stdClass;

$my_object->George = new stdClass;
$my_object->George->birthyear = 1972;
$my_object->George->fav_band = 'The Cure';
$my_object->George->shoe_size = 10;

$my_object->Sally = new stdClass;
$my_object->Sally->birthyear = 1975;
$my_object->Sally->fav_band = 'Coldplay';
$my_object->Sally->shoe_size = 8;

$my_object->Deepak = new stdClass;
$my_object->Deepak->birthyear = 1969;
$my_object->Deepak->fav_band = 'Beach Boys';
$my_object->Deepak->shoe_size = 10;

print count($my_object);

?>