<?php include './assets/components/header.php';
?>

<div class="container">
        <h1 class="text-center my-5">Perfil do Usuário</h1>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title center">Informações</h4>
                        <div class="form-group">
                            <label for="user-name">Nome:</label>
                            <input type="text" class="form-control" id="user-name" value="<?= $user['user_fullname']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="user-username">Nome de usuário:</label>
                            <input type="text" class="form-control" id="user-username" value="<?= $user['user_username']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" value="<?= $user['user_email']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nivel">Nível de Acesso:</label>
                            <input type="text" class="form-control" id="nivel" value="<?= $userAccess['access_level_name']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include './assets/components/footer.php'; ?>
