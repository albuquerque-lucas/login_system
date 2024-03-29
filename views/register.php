<?php include './views/components/header.php'; ?>

    <?php
    if (isset($_COOKIE['errorMessage'])) {
        $message = $_COOKIE['errorMessage'];
    
    ?>
        <div class="alert alert-danger text-center m-3 mx-auto w-50" role="alert">
            <strong>Erro: </strong><?php echo $message; ?>
        </div>
    <?php } ?>
    <div class="container d-flex flex-column align-items-center mt-5">
        <form method="post" action="/create-user">
            <div class="mb-3">
                <label for="username" class="form-label">User name</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3 d-flex w-100">
                <label for="firstname" class="form-label">Nome</label>
                <input type="text" class="form-control" id="firstname" name="firstname">
                <label for="lastname" class="form-label">Sobrenome</label>
                <input type="text" class="form-control" id="lastname" name="lastname">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="check" name="check">
                <label class="form-check-label" for="check">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>

    <?php include './views/components/footer.php'; ?>