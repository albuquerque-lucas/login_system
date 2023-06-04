<?php
    include './assets/components/header.php';
?>

<div class="container">
    <h1 class="text-center">Tarefas</h1>
    <div id="mensagemView">
        <?php 
            if (isset($_COOKIE['authMessage']) || isset($_COOKIE['welcomeMessage'])) {
        ?>
    <div class='alert alert-success'>
        <?php echo $_COOKIE['welcomeMessage'];?>
        <?php echo $_COOKIE['authMessage'];?>
    </div>
<?php } ?>
    </div>

    <?php 
        if ($status) {
    ?>
        <form class="form" action="/create" method="post">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" class="form-control" required autofocus/>
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" id="description" name="description" class="form-control" required/>
            </div>

            <button class="btn btn-primary mt-2" type="submit">Incluir</button>
        </form>
    <?php } ?>
    <br>
    <br>
    <div id="negociacoesView">
        <div class="card">
            <table class="table table-hover table-bordered task-table">
                <thead>
                <tr>
                    <th>NOME</th>
                    <th>DESCRIÇÃO</th>
                    <th>CRIAÇÃO</th>
                    <th>CONCLUSÃO</th>
                    <th>STATUS</th>
                    <?php if ($status){?>
                    <th>CONCLUIR</th>
                    <th>EXCLUIR</th>
                    <?php }?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($tasks as $task) {
                ?>
                <tr>
                    <td class='text-center'>
                        <?= $task['task_name']?>
                    </td>
                    <td>
                        <?= $task['task_description']?>
                    </td>
                    <td class='text-center'>
                        <?= $task['task_creation_date']?>
                    </td>
                    <td class='text-center'>
                        <?= $task['task_conclusion_date'] ?>
                    </td>
                    <td class='text-center'>
                        <?= $task['task_status_name'] ?>
                    </td>
                    <td>
                        <?= $task['task_status_name'] ?>
                    </td>
                    <td class='centralized'>
                        <form action="/delete" method="post">
                            <input type="hidden" name="id" value="<?= $task['task_id']?>" class="d-none">
                            <button type="submit" class='btn btn-sm btn-danger' disabled=<?php $task['task_status'] !== 2?>>
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
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
