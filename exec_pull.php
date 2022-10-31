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
    <?php
    if (isset($_POST['submit'])) {
        $output = shell_exec('bash /home/mmilan/public_html/script.sh');
        var_dump($output);
    }
    ?>

    <h1>Pull Repo</h1>
    <form action="" method="post">
        <input type="submit" value="Pull">
    </form>


</body>

</html>