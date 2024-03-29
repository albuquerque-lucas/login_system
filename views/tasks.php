<?php include './views/components/header.php'; ?>

<div class="container">
    <h1 class="text-center mt-5">Tarefas</h1>

    <?php if ($status) { ?>
        <form class="form" action="/create" method="post">
          <input type="hidden" name="task_user_id" value=<?= $user['user_id']?> />
            <div class="form-group">
                <label for="task_name">Nome</label>
                <input type="text" id="task_name" name="task_name" class="form-control" required autofocus/>
            </div>
            <div class="form-group">
                <label for="task_description">Descrição</label>
                <input type="text" id="task_description" name="task_description" class="form-control" required/>
            </div>
            <button class="btn btn-dark mt-2" type="submit">Incluir</button>
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
                    <?php foreach ($userTasks as $task) { ?>

                        <tr>
                            <td class="input-cell text-center">
                                <span class="cell-value" data-id="<?= $task['task_id'] ?>">
                                    <?= $task['task_name'] ?>
                                </span>
                                <form action="/update-task" method="post" class="text-form">
                                    <input
                                    type="text"
                                    class="form-control input-hidden d-none"
                                    value="<?= $task['task_name'] ?>"
                                    data-namecell="task_name"
                                    >
                                </form>
                            </td>
                            <td class="input-cell">
                                <span class="cell-value" data-id="<?= $task['task_id'] ?>">
                                    <?= $task['task_description'] ?>
                                </span>
                                <form action="/update-task" method="post" class="text-form">
                                    <input
                                    type="text"
                                    class="form-control input-hidden d-none"
                                    value="<?= $task['task_description'] ?>"
                                    data-descriptioncell="task_description"
                                    >
                                </form>
                            </td>
                            <td class="text-center"><?= $task['task_creation_date'] ?></td>
                            <td class="text-center"><?= $task['task_init_date'] ?></td>
                            <td class="text-center"><?= $task['task_conclusion_date'] ?></td>
                            <td class="text-center" id="status-name"><?= $taskModel->getTaskStatus($task['task_id']) ?></td>
                            <?php if ($status) { ?>
                                <td class="align-middle text-center">
                                <?php if ($task['task_status_id'] === 1) { ?>
                                    <form action="/update-task-status" method="post" class="checkbox-form">
                                        <input type="hidden" name="status-zero" value="<?= $task['task_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-primary rounded">
                                            Iniciar
                                        </button>
                                    </form>
                                <?php } else { ?>
                                    <form action="/update-task-status" method="post" class="checkbox-form">
                                        <label for="status-checkbox-<?= $task['task_id'] ?>">Concluir:</label>
                                        <input type="hidden" name="status-zero" value="<?= $task['task_id'] ?>">
                                        <input
                                            id="status-checkbox-<?= $task['task_id'] ?>"
                                            class="status-checkbox"
                                            name="status-checkbox"
                                            type="checkbox"
                                            <?= $task['task_status_id'] === 3 ? "checked" : ""; ?>
                                        >
                                    </form>
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

<?php include './views/components/footer.php'; ?>
