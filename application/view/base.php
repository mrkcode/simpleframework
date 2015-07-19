<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php echo $uri; ?></title>
    <link rel="stylesheet" href="<?php echo $css->tempFile(); ?>">
  </head>
  <body>
    <h1><?php echo $this->content(); ?></h1>
    <h1><?php echo $uri; ?></h1>
  </body>
</html>