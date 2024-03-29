<?php include './views/components/header.php'; ?>

<div class="container d-flex flex-column">
        <h1 class="text-center my-5">Perfil do Usuário</h1>
        <div class="general my-1">
          <h3 class="text-center">Conteúdo geral</h3>
        </div>
            <div class="d-flex justify-content-between my-5">
                <div class="card w-50 mx-5">
                    <div class="card-body">
                        <h4 class="card-title">Informações</h4>
                        <div class="form-group">
                            <label for="user-name">Nome:</label>
                            <input type="text" class="form-control" id="user-name" value="<?= $user['user_fullname']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="user-username">Nome de usuário:</label>
                            <input type="text" class="form-control" id="user-username" value="<?= $user['user_username']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="user-email">Email:</label>
                            <input type="email" class="form-control" id="user-email" value="<?= $user['user_email']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="user-access">Nível de Acesso:</label>
                            <input type="text" class="form-control" id="user-access" value="<?= $userAccess['access_level_name']?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="card w-50 mx-5 d-flex justify-content-center align-items-center">
                      <img src="../assets/images/no-image.jpg" alt="Imagem de perfildo usuário" class="h-100 w-75">
                    </div>
            </div>
            <div class="users-count-data">
              <?php
                include './views/components/totalUsersData.php';
              ?>
            </div>
            <?php if ($user['user_access_level_id'] > 3) { ?>
              <div class="users-list">
                <?php
                  include './views/components/usersList.php';
                ?>
              </div>
              <?php } ?>
    </div>

<?php include './views/components/footer.php'; ?>
