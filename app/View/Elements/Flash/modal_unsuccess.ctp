<!-- Modal -->
<div id="success-alert-modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-filled bg-danger">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="dripicons-wrong h1 text-white"></i>
                    <h4 class="mt-2 text-white">Oh snap!</h4>
                    <p class="mt-3 text-white">
                        <?php
                        echo $message;
                        ?>
                    </p>
                    <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
        // Muestra el modal
        myModal.show();
    });
</script>