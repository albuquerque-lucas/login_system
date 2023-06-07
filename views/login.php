<?php include './assets/components/header.php'; ?>

<?php
if (isset($_SESSION['errorMessage'])) {
    $message = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']);
    unset($_SESSION['messageType']);
?>
    <div class="alert alert-danger mx-auto w-50" role="alert">
        <strong>Erro: </strong><?php echo $message; ?>
    </div>
<?php } ?>

<div class="container d-flex align-items-center justify-content-center mt-5" style="height:55vh;">
    <form method="post" action="/authenticate">
        <div class="mb-3">
            <label for="username" class="form-label">Email or User name</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Entrar</button>
            <button type='button' class="btn btn-dark">
                <a href="/register" class="text-white text-decoration-none">Register</a>
            </button>
        </div>
    </form>
</div>

<?php include './assets/components/footer.php'; ?>
