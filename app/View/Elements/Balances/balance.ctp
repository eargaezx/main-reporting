<div class="row">
    <table class="table table-striped table-bordered" style="margin-bottom: 0px;">
        <thead>
            <tr>
                <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                    Periodo
                </th>
                <th>
                 <?=  
                        [ 
                            '7' => 'SEMANAL',
                            '15' => 'QUINCENAL',
                            '30' => 'MENSUAL'
                        ][$data['Balance']['period']] 
                    ?>
                </th>
                <th>
                        <?= $data['Balance']['range'] ?>
                </th> 
                <th  style="background-color: #124968;color:#fff; padding-left: 10px;">
                    Comisión  
                </th> 
                <th >
                        <?= $data['Balance']['comission'] ?>% 
                </th>
                <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                    Monto
                </th>
                <th colspan="3">
                        <?= '$'.number_format($data['Balance']['comission_ammount'], 2) ?>
                </th>


                <th rowspan="2" >
                    <?php
                        if (empty($data['Balance']['invoice'])){
                            unset($modelActions[1]);
                        } 
                    ?>
                    
                    <?PHP foreach ($modelActions as $modelAction): ?>
                    <a href=" <?=
                                                   Router::url((['action' => $modelAction['action']] + (isset($modelAction['controller']) ? ['controller' => $modelAction['controller']] : []) + [$data[$modelName]['id']]), true)
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



                    <?php if (!empty($data['Balance']['voucher'])): ?>
                        <a data-toggle="tooltip" 
                           data-placement="bottom"
                           data-original-title="Comprobante"
                           class="btn btn-sm btn-outline-dark waves-effect waves-light"  
                           target="_blank" href="<?= $data['Balance']['voucher'] ?>"> <i class="fe-paperclip"></i>
                        </a>
                    <?php endif; ?>

                </th>


            </tr> 

            <tr>
                <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                    Fecha Pago
                </th>
                <th colspan="4">
                        <?php if (!empty($data['Balance']['payment_date']) && $data['Balance']['payment_date'] != '0000-00-00'): ?>
                            <?= $data['Balance']['payment_date'] ?>
                        <?php else: ?>
                    Sin pagar
                        <?php endif; ?>
                </th>
                <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                    Pagado
                </th>
                <th>
                       <?= '$'.number_format($data['Balance']['amount_paid'], 2) ?>
                </th>
                <th style="background-color: #124968;color:#fff; padding-left: 10px;">
                    Adeudo
                </th>
                <th>
                       <?= '$'.number_format($data['Balance']['amount_debt'], 2) ?>
                </th>

            </tr>




        </thead>
    </table>
</div>