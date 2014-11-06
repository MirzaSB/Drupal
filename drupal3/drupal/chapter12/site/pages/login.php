<?php
$title = 'Log in';

if (isset($_SESSION['user'])) {
  $content = '
    <h1>Welcome, ' . $_SESSION['user']['username'] . '</h1>
    <p>You are logged in, enjoy!</p>
    <ul>
      <li><a href="' .  url('admin/users.php') . '">Administer users</a></li>
      <li><a href="' .  url('admin/products.php') . '">Administer products</a></li>
      <li><a href="' .  url('admin/pages.php') . '">Administer pages</a></li>
    </ul>';
} else {
  $content = '
  <h1>'. $title . '</h1>
  <form action="login.php" method="post">
    <p>Username: <input type="text" name="username" /></p>
    <p>Password: <input type="password" name="password" /></p>
    <p><input type="submit" value="Log in" />
    <input type="hidden" name="action" value="login" />
  </form>';
}
