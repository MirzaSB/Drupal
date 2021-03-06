<?php

// 1. The object equivalent of an associative array.
$my_object = new stdClass;

$my_object->George = 1972;
$my_object->Sally = 1975;
$my_object->Deepak = 1869;

// Accessing object properties.
print $my_object->George . '<br />';


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

// Accessing a property of a property
print $my_object->Sally->fav_band . '<br />';

?>