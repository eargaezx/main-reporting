<script>
    document.addEventListener('DOMContentLoaded', function () {
        swal({
            title: "<?= $title ?>",
            text: "<?= $message ?>",
            confirmButtonClass: "btn-success",
            imageUrl: "<?= $image ?>"
        });
    });
</script>
