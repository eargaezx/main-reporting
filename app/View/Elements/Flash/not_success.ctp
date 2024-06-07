<script>
    document.addEventListener('DOMContentLoaded', function () {
        swal({
            title: "Operación no completada",
            text: "<?= $message ?>",
            type: "error",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Hecho"
        });
    });
</script>
