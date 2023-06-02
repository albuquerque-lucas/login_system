<?php
require './vendor/autoload.php';
use LucasAlbuquerque\LoginSystem\Controller\AuthController;
$authController = new AuthController();
$sessionInfo = $authController->checkSessionStatus();
list($status, $user) = $sessionInfo;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/styles/style.css">
        <script src="https://kit.fontawesome.com/9aa910470c.js" crossorigin="anonymous"></script>
    <title>Login</title>
</head>

<body>

    <header>

        <nav class='navbar'>
            <ul>
                <li><a href="/home">Home</a></li>
                <li><a href="/register">Sign in</a></li>
                <?php if ($status){?>
                <form action="/logout" method="post">
                <input type="hidden" name="userid" value="<?php echo $user['user_id']; ?>">
                    <li>
                        <button type="submit">Logout</button>
                    </li>
                </form>

                <?php } else{?>
                    <li><a href="/login">Login</a></li>
                    <?php } ?>
            </ul>
        </nav>

    </header>