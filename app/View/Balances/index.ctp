
<?PHP if ($this->request->ext != 'ajax'): ?>
    <div class="card small-padding col-sm-12" style="padding-left: 0px; padding-right: 0px; padding-bottom: 0px;">
        <div class="card-header card-header-transparent card-header-bordered font-weight-500 font-size-17">
            Balance
        </div>
        <div class="card-block" style="padding: 0px!important;">
        <?PHP endif; ?>



        <?PHP
        if ($this->request->ext != 'ajax'):
            //echo pr($modelFields); die();
            ?>
            <?PHP
            $table_component_id = CakeText::uuid();
            echo $this->Form->create('filter', [
                'id' => $table_component_id,
                'class' => 'form-horizontal match-height ',
                'inputDefaults' => [
                    'label' => FALSE,
                    'class' => 'form-control',
                    'fieldset' => FALSE,
                    'format' => FALSE,
                    'div' => FALSE
                ]
            ])
            ?>
            <table class="table table-sm table-striped table-bordered table-actions-bar fixed-table smart-table"
                   data-table-action = "<?= Router::url(['controller' => $controllerName, 'action' => $actionName, ''], true) ?>"
                   data-paginate-limit="<?= isset($limit) ? $limit : 10 ?>"
                   data-last-page="1"
                   data-updating="false">
                <thead>

                    <tr>
                        <th style="background-color: #124968;color:#fff; padding-left: 10px; ">
                            <img src="<?= $business['Business']['logo'] ?>" width="30px" style="border-radius: 50%;">
                            <?= $business['Business']['name'] ?>
                        </th>

                        <th style="background-color: #124968;color:#fff; padding-left: 10px; ">
                            CLAVE
                        </th>
                        <th>
                            A<?= $business['Business']['reference'] ?>
                        </th>

                        <th>

                        </th>

                        <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                            Total Pagado
                        </th>
                        <th>
                            <?= '$' . number_format(array_sum(Hash::extract($data, '{n}.{s}.amount_paid')), 2) ?>
                        </th>
                        <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                            Total Adeudo
                        </th>
                        <th>
                            <?= '$' . number_format(array_sum(Hash::extract($data, '{n}.{s}.amount_debt')), 2) ?>
                        </th>
                        <th >

                            <div class="btn-group" role="group">

                                <?PHP
                                unset($controllerActions[0]);
                                unset($controllerActions[1]);
                                foreach ($controllerActions as $controllerAction):
                                    ?>

        <?PHP if (isset($controllerAction['type']) && $controllerAction['type'] == 'submit'): ?>
                                        <button data-toggle="tooltip" 
                                                data-placement="bottom"
                                                data-original-title="<?= isset($controllerAction['title']) ? $controllerAction['title'] : '' ?>"
                                                type="<?= isset($controllerAction['type']) ? $controllerAction['type'] : 'button' ?>"
                                                class="<?= isset($controllerAction['class']) ? $controllerAction['class'] : '' ?>">
                                            <i class="<?= isset($controllerAction['icon']['class']) ? $controllerAction['icon']['class'] : '' ?>">
            <?= isset($controllerAction['icon']['text']) ? $controllerAction['icon']['text'] : '' ?>
                                            </i>
                                        </button>
                                    <?PHP else: ?>       
                                        <a href="<?=
                                           Router::url(['action' => $controllerAction['action']] + (isset($controllerAction['controller']) ? ['controller' => $controllerAction['controller']] : []) + (isset($controllerAction['params']) ? $controllerAction['params'] : []))
                                           ?>"
                                           data-toggle="tooltip" 
                                           data-placement="bottom"
                                           data-original-title="<?= isset($controllerAction['title']) ? $controllerAction['title'] : '' ?>"
                                           type="<?= isset($controllerAction['type']) ? $controllerAction['type'] : 'button' ?>"
                                           class="<?= isset($controllerAction['class']) ? $controllerAction['class'] : '' ?>">
                                            <i class="<?= isset($controllerAction['icon']['class']) ? $controllerAction['icon']['class'] : '' ?>">
            <?= isset($controllerAction['icon']['text']) ? $controllerAction['icon']['text'] : '' ?>
                                            </i>
                                        </a>
                                    <?PHP endif; ?>

    <?PHP endforeach ?>
                            </div>

                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                    </tr>

                    <tr>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                        <td style="background-color: #dee2e6;"></td>
                    </tr>


    <?PHP foreach ($data as $rowData): ?>


                        <tr>

                            <td style="background-color: #124968;color:#fff; padding-left: 10px; border: 0px solid #dee2e6;">
                                <?=
                                [
                                    '7' => 'SEMANAL',
                                    '15' => 'QUINCENA',
                                    '30' => 'MENSUALIDAD'
                                ][$rowData['Balance']['period']]
                                ?>
                            </td>

                            <td style="font-weight: bold;">
        <?= $rowData['Balance']['range'] ?>
                            </td>

                            <td style="background-color: #124968;color:#fff; padding-left: 10px;">
                                Comision
                            </td>
                            <td>
        <?= $rowData['Balance']['comission'] ?>%
                            </td>
                            <td style="background-color: #124968;color:#fff; padding-left: 10px;">
                                Monto
                            </td>
                            <td>
        <?= '$' . number_format($rowData['Balance']['comission_ammount'], 2) ?>
                            </td>
                            <td >

                            </td>
                            <td>

                            </td>
                            <td rowspan="2" style="vertical-align: middle;text-align: center;"> 
                                <?PHP foreach ($modelActions as $modelAction): ?>
                                    <a href=" <?=
                                       Router::url((['action' => $modelAction['action']] + (isset($modelAction['controller']) ? ['controller' => $modelAction['controller']] : []) + [$rowData[$modelName]['id']]), true)
                                       ?>"
                                       data-toggle="tooltip" 
                                       data-placement="bottom"
                                       data-original-title="<?= isset($modelAction['title']) ? $modelAction['title'] : $modelAction['action'] ?>"
                                       class="<?= isset($modelAction['class']) ? $modelAction['class'] : '' ?>">
                                        <i class="<?= isset($modelAction['icon']['class']) ? $modelAction['icon']['class'] : '' ?>">
            <?= isset($modelAction['icon']['text']) ? $modelAction['icon']['text'] : '' ?>
                                        </i>
                                    </a>
        <?PHP endforeach; ?>
                            </td>

                        </tr>

                        <tr>

                            <td style="background-color: #124968;color:#fff; padding-left: 10px; border: 0px solid #dee2e6;">
                                Fecha Pago
                            </td>

                            <td>
                                <?php if (!empty($rowData['Balance']['payment_date']) && $rowData['Balance']['payment_date'] != '0000-00-00'): ?>
                                    <?= $rowData['Balance']['payment_date'] ?>
                                <?php else: ?>
                                    Sin pagar
        <?php endif; ?>
                            </td>


                            <td style="background-color: #124968;color:#fff; padding-left: 10px;">
                                Comprobante
                            </td>
                            <td>
                                <?php if (!empty($rowData['Balance']['voucher'])): ?>
                                    <a  target="_blank" href="<?= $rowData['Balance']['voucher'] ?>"> <i class="fe-paperclip"></i></a>
                                <?php else: ?>
                                    NA
        <?php endif; ?>
                            </td>


                            <td style="background-color: #82d178;color:#fff; padding-left: 10px;">
                                Pagado
                            </td>
                            <td>
        <?= '$' . number_format($rowData['Balance']['amount_paid'], 2) ?>
                            </td>


                            <td style="background-color: #ee7777;color:#fff; padding-left: 10px;">
                                Adeudo
                            </td>
                            <td>
        <?= '$' . number_format($rowData['Balance']['amount_debt'], 2) ?>
                            </td>


                        </tr>

                        <tr>
                            <td colspan="9" style="height: 33px; background-color: #edeff1">

                            </td>
                        </tr>




    <?PHP endforeach; ?>
                </tbody>

            </table>
    <?= $this->Form->end() ?>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    jQuery(document).ready(function () {
                        var self = this;
                        this.table = $("#<?= $table_component_id ?>").find(".jqstb-scroll").first().find("table").first();
                        this.updateTable = function () {
                            self.page = $(self.table).data('last-page') + 1;
                            self.action = $(self.table).data("table-action") + '/page:' + self.page + '.ajax';
                            //this.data = JSON.parse(atob($(self.table).data('valid-filters')));

                            if ($(self.table).data('updating') == true)
                                return;

                            $(self.table).data('updating', true);

                            $.ajax({
                                url: self.action,
                                type: 'post',
                                data: {
                                    named: {
                                        filter: {}
                                    }
                                },
                                success: function (response) {
                                    $(self.table).data('last-page', self.page);
                                    $(self.table).find('tbody').append(response).find('.swal-confirm').click(function (e) {
                                        e.preventDefault();
                                        var message = $(this).data("message");
                                        var action = $(this).attr("href");

                                        swal({
                                            title: "Atención",
                                            text: message,
                                            type: "warning",
                                            showCancelButton: true,
                                            confirmButtonClass: "btn-danger",
                                            confirmButtonText: "Si, continuar",
                                            cancelButtonText: "No, regresar",
                                            closeOnConfirm: false,
                                            closeOnCancel: true}).then(function () {
                                            window.location = action;
                                        });
                                    });

                                    $(self.table).trigger("update");
                                    //var count = $(self.table).find("tbody").find("tr").length;
                                    //$(self).find('.counter').first().text(count);
                                    $(self.table).data('updating', false);
                                }
                            });
                        };

                        if ($(self.table).data('paginate-limit') == $(self.table).find("tbody").first().find("tr").length) {
                            self.updateTable();
                        }

                        $('.jqstb-scroll').on('scroll', function () {
                            if (Math.round($(this).scrollTop() + $(this).innerHeight(), 10) >= Math.round($(this)[0].scrollHeight, 10)) {
                                self.updateTable();
                            }
                        });


                    });
                }, false);
            </script>
<?PHP endif; ?>





<?PHP if ($this->request->ext != 'ajax'): ?>   
        </div>
    </div>
<?PHP endif; ?>


