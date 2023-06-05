// Submete o formulário de alteração de status da Task
const checkboxForm = document.getElementById('checkbox-form');
const statusCheckbox = document.getElementById('status-checkbox');

function submitForm(form) {
    form.submit();
}
if(statusCheckbox) {
  statusCheckbox.addEventListener('change', () => {
    if(!statusCheckbox.checked) {
        submitForm(checkboxForm);
    } else {
        submitForm(checkboxForm);
    }

});
}

// Captura todas as células que podem ser editadas
function toggleElementVisibility(elementToShow, elementToHide) {
  elementToShow.classList.remove('d-none');
  elementToHide.classList.add('d-none');
}

const textCells = document.querySelectorAll('.cell-value');
const textForms = document.querySelectorAll('.text-form');
const tdCell = document.querySelectorAll('.input-cell');
const hiddenInputs = document.querySelectorAll('.input-hidden');
let editMode = false;

if (tdCell.length > 0) {
  tdCell.forEach((cell) => {
    cell.addEventListener('dblclick', () => {
      const spanTag = cell.querySelector('.cell-value');
      const cellInput = cell.querySelector('.input-hidden');
      let column;
      if (editMode === false) {
        toggleElementVisibility(cellInput, spanTag);
        editMode = true;
      } else {
        toggleElementVisibility(spanTag, cellInput);
        if (cellInput.dataset.namecell === 'task_name') {
          column = 'task_name';
        } else {
          column = 'task_description';
        }
        updateTextData(spanTag.dataset.id, cellInput.value, column);
        spanTag.textContent = cellInput.value;
        editMode = false;
      }
    });
  });

}

async function updateTextData(taskId, text, column) {
  const url = '/update-name-description';
  const data = {
    taskId,
    text,
    column,
  };
  console.log('column', column);
  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  };
  try {
    const response = await fetch(url, options);
    if (response.ok) {
      console.log('Dados enviados com sucesso!');
    }
  } catch(error) {
    console.log('Erro ao enviar os dados: ', error);
  };
}