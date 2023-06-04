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
            <label for="task_name">Nome</label>
            <input type="text" id="task_name" name="task_name" class="form-control" required autofocus/>
        </div>
        <div class="form-group">
            <label for="task_description">Descrição</label>
            <input type="text" id="task_description" name="task_description" class="form-control" required/>
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
                        <th class="text-center">NOME</th>
                        <th class="text-center">DESCRIÇÃO</th>
                        <th class="text-center">CRIAÇÃO</th>
                        <th class="text-center">INÍCIO</th>
                        <th class="text-center">CONCLUSÃO</th>
                        <th class="text-center">STATUS</th>
                        <?php if ($status) { ?>
                            <th class="text-center">CONCLUIR</th>
                            <th class="text-center">EXCLUIR</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task) { ?>
                        <tr>
                            <td class="text-center"><?= $task['task_name'] ?></td>
                            <td><?= $task['task_description'] ?></td>
                            <td class="text-center"><?= $task['task_creation_date'] ?></td>
                            <td class="text-center"><?= $task['task_init_date'] ?></td>
                            <td class="text-center"><?= $task['task_conclusion_date'] ?></td>
                            <td class="text-center"><?= $task['task_status_name'] ?></td>
                        <?php if ($status) { ?>
                            <td class="align-middle text-center">
                                <?php if ($task['task_status'] === 0) {?>
                                <button type="button" class="btn btn-sm btn-primary rounded">Iniciar</button>
                                <?php } else { ?>
                                <label for="input-status">Concluir :</label>
                                <input type="checkbox" checked=<?php $task['task_status'] === 2?>>
                                <?php } ?>
                            </td>
                            <td class="align-middle text-center">
                                <form action="/delete" method="post">
                                    <input type="hidden" name="id" value="<?= $task['task_id'] ?>" class="d-none">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
include './assets/components/footer.php';
?>
