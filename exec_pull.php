<?php

/**
 * @author     Mathis Lambert
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pull Repo</title>
</head>

<body>
    <?php
    $output = shell_exec('git pull git@github.com:arthur-mdn/mmilan');
    echo "<pre>$output</pre>";
    ?>

    <h1>Pull Repo</h1>
    <form action="" method="post">
        <input type="submit" value="Pull">
    </form>


</body>

</html>