<div class="card">
    <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
    <a href="javascript:window.history.back();">
            <i class="fe-arrow-left font-size-22" style="color:black"></i>
            </a>
        <?= $singularDisplayName . ' Details' ?>
    </div>
    <div class="card-block px-1">

        <?= $this->element('Form/read', [
            'formClass' => 'readonly'
        ]) ?>



        <table class="table table table-bordered table-striped">
            <thead>
            </thead>
            <tbody>
                <?php
                foreach ($questions as $question): ?>
                    <tr>
                        <td class="font-weight-bold px-4" style="width:1%; white-space:nowrap;">
                            <?= $question['question'] ?>
                        </td>
                        <td class="font-weight-normal">
                            <?PHP
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

        <div class="row col-md-12">
            <div class="col-md-4 col-sm-6">
                <img src="<?= $this->request->data['Order']['image_evidence_1'] ?>" style="width: 100%; object-fit:contain">
            </div>
            <div class="col-md-4 col-sm-6">
                <img src="<?= $this->request->data['Order']['image_evidence_2'] ?>" style="width: 100%; object-fit:contain">
            </div>
            <div class="col-md-4 col-sm-6">
                <img src="<?= $this->request->data['Order']['image_evidence_3'] ?>" style="width: 100%; object-fit:contain">
            </div>
            <div class="col-md-4 col-sm-6">
                <img src="<?= $this->request->data['Order']['image_evidence_4'] ?>" style="width: 100%; object-fit:contain">
            </div>
            <div class="col-md-4 col-sm-6">
                <img src="<?= $this->request->data['Order']['image_evidence_5'] ?>" style="width: 100%; object-fit:contain">
            </div>
            <div class="col-md-4 col-sm-6">
                <img src="<?= $this->request->data['Order']['image_evidence_6'] ?>" style="width: 100%; object-fit:contain">
            </div>
        </div>

    </div>
</div>