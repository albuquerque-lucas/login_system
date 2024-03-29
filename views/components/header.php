<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/9aa910470c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Login System</title>
</head>

<body class='body'>
<header>
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg d-flex flex-column">
        <ul class="navbar-nav w-25 d-flex justify-content-between align-self-center">
            <li class="nav-item">
                <a class="nav-link" href="/home">Home</a>
            </li>
            <?php if ($status) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/profile">Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/tasks">Tarefas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/messages">Mensagens</a>
                </li>
                <li class="nav-item">
                    <form action="/logout" method="post">
                        <input type="hidden" name="userid" value="<?= $user['user_id']; ?>">
                        <button class="btn btn-outline-light" type="submit">Logout</button>
                    </form>
                </li>

            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/register">Sign in</a>
                </li>
                <li class="nav-item rounded">
                    <a class="nav-link text-white" href="/login">Login</a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</header>

