<script>
    document.addEventListener('DOMContentLoaded', function () {
        $("#modalMain").modal();
        setTimeout(function () {
            $("#modalMain").find(".modal-content:visible").first().hide();
            $("#modal-content-restore").show("slow");
        }, 300);
    });
</script>
