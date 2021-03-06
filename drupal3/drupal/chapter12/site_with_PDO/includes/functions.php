<?php

include('database.php');
  
// Takes array of product IDs and returns a rendered product list.
function render_products($product_ids = '') {
  
  // So we only need to include the product array once.
  static $products;
  
  // Pull in the data.
  if (!isset($products)) {
    include('data/product-data.php');
  }
  
  // Allow an empty value to display all the products.
  if ($product_ids == '') {
    $product_ids = array();
    foreach ($products as $pid => $options) {
      $product_ids[] = $pid;
    }
  }
  
  // This way we can pass one number or an array to the function.
  if (!is_array($product_ids)) {
    $product_ids = array($product_ids);
  }
  
  // Render each product.
  $output = '';
  
  foreach ($product_ids as $pid) {
    $output .= '
      <div class="product">
        <div class="product-img"><img src="images/' . $products[$pid]['img'] . '" /></div>
        <div class="product-price">$' . $products[$pid]['price'] . '</div>
        <div class="product-title">' . $products[$pid]['title'] . '</div>
        <a class="cart-button" href="#">Add to cart</a>
      </div>';
  }
  
  return $output;
}


// Returns a setting from settings.php.
function get_setting($name) {
  static $settings;
  
  if (!isset($settings)) {
    include('settings/settings.php');
  }
  
  return $settings[$name];
}


// Connect to the database.
function db_connect() {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    if (!$pdo) {
        die('<strong>You were not able to connect to your database</strong>');
    }

}


// We're setting up a function to pull together notices to the user and display them.
function notice($text, $action = 'add') {
  static $notices;
  if ($action == 'add') {
    $notices[] = $text;
  } elseif ($action == 'get') {
    if (count($notices) > 0) {
      $output = '<div class="notices"><ul><li>' . implode('</li><li>', $notices) . '</li></ul></div>';
      unset($notices);
      return $output;
    }
  }
}


// A shortcut function that's more intuitive to use for getting the notices.
function get_notices() {
  return notice('', 'get');
}