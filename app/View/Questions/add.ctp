<?PHP if ($this->request->ext != 'ajax'): ?>

    <div class="card ">
        <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
            Add Question
        </div>
        <div class="card-block p-4">
        <?PHP endif; ?>

        <?= $this->element('Form/form') ?>

        <?PHP if ($this->request->ext != 'ajax'): ?>
        </div>
    </div>
<?PHP endif; ?>


<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        jQuery(document).ready(function () {
            function onQuestionType(selectedValue) {
                if (selectedValue !== 'options') {
                    $('#QuestionOptions').parent().parent().parent().hide();
                } else {
                    $('#QuestionOptions').parent().parent().parent().show();
                }
            }

            $('#QuestionType').change(function () {
                onQuestionType($(this).val());
            });

            onQuestionType($('#QuestionType').val());
        })
    });
</script>