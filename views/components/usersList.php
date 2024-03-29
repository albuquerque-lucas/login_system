<div class="card">
            <table class="table table-hover table-bordered task-table">
                <thead>
                    <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Nome</th>
                        <th class="text-center">E-mail</th>
                        <th class="text-center">Nível de acesso</th>
                        <th class="text-center">Editar</th>
                        <th class="text-center">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $itemUser) { ?>

                        <tr>
                            <td class="text-center">
                              <?= $itemUser['user_id'] ?>
                            </td>
                            <td class="input-cell">
                              <?= $itemUser['user_fullname'] ?>
                            </td>
                            <td><?= $itemUser['user_email'] ?></td>
                            <td><?= $itemUser['access_level_id'] ?></td>
                            <td class="align-middle text-center">
                                    <form action="/user-update" method="post">
                                        <input type="hidden" name="id" value="<?= $itemUser['user_id'] ?>" class="d-none">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="align-middle text-center">
                                    <form action="/user-delete" method="post">
                                        <input type="hidden" name="id" value="<?= $itemUser['user_id'] ?>" class="d-none">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                              </tr>
                              <?php } ?>
                </tbody>
            </table>
        </div>