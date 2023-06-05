<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script type="module">
    const checkboxForm = document.getElementById('checkbox-form');
    const statusCheckbox = document.getElementById('status-checkbox');
    function submitForm() {
        checkboxForm.submit();
    }
    statusCheckbox.addEventListener('change', () => {
        if(!statusCheckbox.checked) {
            submitForm();
        } else {
            submitForm();
        }

    })
    // import { updateStatus } from '../../index.js';
    // const initBtn = document.getElementById('init-btn');
    // const statusNameCell = document.getElementById('status-name');
    // if (initBtn) {
    // const initStatus = Number(initBtn.dataset.status);
    // const taskId = Number(initBtn.dataset.id);
    // initBtn.addEventListener('click', (event) => {
    //     updateStatus(taskId, initStatus);
    //     if (initStatus === 0) {
    //         statusNameCell.textContent = "Iniciada / Pendente"
    //     }
    // });
    // }
    // const statusCheckBox = document.getElementById('status-checkbox');
    // if (statusCheckBox) {
    //     const checkId = Number(statusCheckBox.dataset.checkid);
    //     const checkStatus = Number(statusCheckBox.dataset.checkstatus);
    //     statusCheckBox.addEventListener('change', () => {
    //     updateStatus(checkId, checkStatus);
    //     if (checkStatus === 1) {
    //         statusNameCell.textContent = "Finalizada";
    //     } else if (checkStatus === 2) {
    //         statusNameCell.textContent = "Iniciada / Pendente";
    //     }
    //     });
    // }

</script>
</body>
</html>
