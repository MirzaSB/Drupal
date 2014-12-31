<?php

include('includes/crud.php');

$title = 'Administer settings';

$content = "<div align='center'><b><u>UNDER CONSTRUCTION</u></b></div>";

$options = array(
    'table' => 'settings'
);

$content .= displaySettingsSelect($options);

?>