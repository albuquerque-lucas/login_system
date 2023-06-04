<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script type="module">
    import { updateStatus } from '../../index.js';
    const initBtn = document.getElementById('init-btn');
    const initStatus = Number(initBtn.dataset.status);
    const taskId = Number(initBtn.dataset.id);
    initBtn.addEventListener('click', (event) => {
        updateStatus(taskId, initStatus);
    })
</script>
</body>
</html>
