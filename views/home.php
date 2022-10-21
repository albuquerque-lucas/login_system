<?php
include './assets/components/header.php';
?>


<div class="container">

    <h1 class="text-center">Tarefas</h1>

    <div id="mensagemView"></div>

    <?php if ($check){ ?>

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
                    <?php if ($check){?>
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
                        <?= $task['name']?>
                    </td>
                    <td>
                        <?= $task['description']?>
                    </td>
                    <td class='text-center'>
                        <?= $task['creationDate']?>
                    </td>
                    <td class='text-center'>
                        <?php if( $task['conclusionDate'] == null){?>
                            Pendente
                        <?php } else{ ?>
                            <?= $task['conclusionDate'] ?>
                        <?php } ?>
                    </td>
                    <td class='text-center <?php if($task['status'] == 0){?>bg-warning<?php } else{?>bg-success text-light<?php }?>'>
                        <?php if($task['status'] == 0){ ?>
                            Pendente
                        <?php } else{ ?>
                            Concluída
                        <?php } ?>
                    </td>

                    <?php if ($check){ ?>
                    <td class='text-center'>
                        <form action="/concludeTask" method="post">
                            <input type="hidden" name="id" value=<?= $task['id']?>>
                            <input
                                    type="checkbox"
                                    name="status"
                                <?php if($task['status'] == 0){ ?>
                                    value="null"
                                <?php }
                                else{ ?>
                                    value="1"
                                <?php } ?>
                                <?php if($task['status'] ==! 0){?>
                                    checked
                                <?php } ?>
                            >
                            <button type="submit" class="btn btn-sm btn-dark"><i class="fa-solid fa-check"></i></button>
                        </form>
                    </td>
                    <?php }?>
                    <?php if ($check){ ?>
                        <td class='centralized'>
                            <form action="/delete" method="post">
                                <input type="hidden" name="id" value="<?= $task['id']?>" class="d-none">
                                <button type="submit" class='btn btn-sm btn-danger'><i class="fa-solid fa-trash"></i></button>
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
