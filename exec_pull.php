<?php

/**
 * @author     Mathis Lambert
 */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pull Repo</title>
</head>

<body>

    <h1>Pull Repo</h1>
    <form action="" method="post">
        <input type="submit" name="submit" value="Pull">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $output = shell_exec('sh /home/mmilan/public_html/script.sh > /home/mmilan/logs/logs.txt &');
        echo "<pre>$output</pre>";
        echo "data updated";
    }
    ?>

</body>

</html>