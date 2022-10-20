<?php
include './assets/components/header.php';
?>


<div class="container">
    <h1 class="text-center">Tarefas</h1>

    <div id="mensagemView"></div>
    <?php if ($check){?>
        <form class="form" action="/create" method="post">

            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" class="form-control" required autofocus/>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" id="description" name="description" class="form-control" required/>
            </div>

            <button class="btn btn-primary" type="submit">Incluir</button>
        </form>
    <?php }?>

    <br>
    <br>
    <div id="negociacoesView">
        <div class="card">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>NOME</th>
                    <th>DESCRIÇÃO</th>
                    <?php if ($check){?>
                    <th>EXCLUIR</th>
                    <?php }?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($tasks as $task) {
                ?>
                <tr>
                    <td>
                        <?= $task['name']?>
                    </td>
                    <td>
                        <?= $task['description']?>
                    </td>
                    <?php if ($check){?>
                        <td>
                            <form action="/delete" method="post">
                                <input type="text" name="id" value="<?= $task['id']?>" class="d-none">
                                <button type="submit">Deletar</button>
                            </form>
                        </td>
                    <?php }?>
                </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
include './assets/components/footer.php';
?>
