<?php
include 'content.php';

$tag = showPlugin();

function plugins_url($name, $param)
{
    return $name;
}

echo "
<html>
    <head>
        <link rel='stylesheet' href='prayer-styles.css'>
    </head>
    <body>
        <h1>Hallo</h1>
        <p id='testnumber'></p>
        $tag
    </body>
</html>
";
