<?php

include('includes/crud.php');

$title = 'Administer settings';

$content = "<div align='center'><b><u>FEATURED STICKS SETTINGS PAGE</u></b></div>";

$options = array(
    'table' => 'settings'
);

$content .= displaySettingsSelect($options);

?>