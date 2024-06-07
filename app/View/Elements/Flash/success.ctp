<script>
    document.addEventListener('DOMContentLoaded', function () {
        swal({
            title: "Acción completada",
            text: "<?= $message ?>",
            type: "success",
            confirmButtonClass: "btn-success",
            confirmButtonText: "Hecho"
        });
    });
</script>
