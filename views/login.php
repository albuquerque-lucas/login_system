<?php
include './assets/components/header.php';


?>

<?php
    if (isset($_COOKIE['errorMessage'])) {
        $message = $_COOKIE['errorMessage'];
    
    ?>
        <div class="alert alert-danger text-center m-3 mx-auto w-50" role="alert">
            <strong>Erro: </strong><?php echo $message; ?>
        </div>
    <?php } ?>
    <div class="container d-flex flex-column align-items-center mt-5">
        <form method="post" action="/authenticate">
            <div class="mb-3">
                <label for="username" class="form-label">Email or User name</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="check" name="check">
                <label class="form-check-label" for="check">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href='/register'>Register</a>
        </form>
    </div>


<?php
include './assets/components/footer.php'
?>