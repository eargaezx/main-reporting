<div class="card">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
        <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
        </a>
        <?= $singularDisplayName . ' Details' ?>
    </div>
</div>

<div class="accordion" id="detailsAccordion">
    <!-- Primer Acordeón -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingForm">
            
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseForm" aria-expanded="true" aria-controls="collapseForm">
                <strong>Order Details</strong>
            </button>
        </h2>
        <div id="collapseForm" class="accordion-collapse collapse" aria-labelledby="headingForm"
            data-bs-parent="#detailsAccordion">
            <div class="accordion-body">
                <?= $this->element('Form/read', ['formClass' => 'readonly']) ?>
            </div>
        </div>
    </div>

    <!-- Segundo Acordeón -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingQuestions">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseQuestions" aria-expanded="false" aria-controls="collapseQuestions">
                <strong>Quiz</strong>
            </button>
        </h2>
        <div id="collapseQuestions" class="accordion-collapse collapse" aria-labelledby="headingQuestions"
            data-bs-parent="#detailsAccordion">
            <div class="accordion-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($questions as $question): ?>
                            <tr>
                                <td class="font-weight-bold"><?= $question['question'] ?></td>
                                <td>
                                    <?php
                                    $questionId = $question['id'];
                                    $answers = array_filter($this->request->data['QuestionAnswer'], function ($item) use ($questionId) {
                                        return $item['question_id'] == $questionId;
                                    });

                                    echo empty(reset($answers)['value']) ? '' : reset($answers)['value'];
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tercer Acordeón -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingImages">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImages"
                aria-expanded="false" aria-controls="collapseImages">
                <strong>Images</strong>
            </button>
        </h2>
        <div id="collapseImages" class="accordion-collapse collapse show" aria-labelledby="headingImages"
            data-bs-parent="#detailsAccordion">
            <div class="accordion-body">
                <div class="row">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <?php if (!empty($this->request->data['Order']["image_evidence_$i"])): ?>
                            <div class="col-md-4 col-sm-6">
                                <img src="<?= $this->request->data['Order']["image_evidence_$i"] ?>"
                                    style="width: 100%; object-fit:contain">
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>