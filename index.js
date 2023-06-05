export const updateStatus = async (taskId, status) => {
  const url = '/update-task';
  const data = {
    taskId,
    status,
  }
  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  }

  try {
    const response = await fetch(url, options);
    if (response.ok) {
      console.log('Alteração efetuada com sucesso!');
      // Realize qualquer outra ação necessária após a alteração
    } else {
      throw new Error('Falha na alteração');
    }
  } catch (error) {
    console.error('Erro na requisição:', error);
    // Realize o tratamento adequado do erro
  }
}