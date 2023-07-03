<?php include './views/components/header.php'; ?>

<div class="container">
    <h1 class="text-center mt-5">Início</h1>
    <div id="mensagemView">
        <?php if (isset($_SESSION['authMessage']) || isset($_SESSION['welcomeMessage'])) { ?>
            <div class='alert alert-success'>
                <?= $_SESSION['welcomeMessage']; ?>
                <?= $_SESSION['authMessage']; ?>
            </div>
        <?php
            unset($_SESSION['welcomeMessage']);
            unset($_SESSION['authMessage']);
            unset($_SESSION['messageType']);
        }
        ?>
    </div>

    
</div>

<?php include './views/components/footer.php'; ?>
