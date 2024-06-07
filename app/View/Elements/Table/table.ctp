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
        data-table-action="<?= Router::url(['controller' => $controllerName, 'action' => $actionName, ''], true) ?>"
        data-paginate-limit="<?= isset($limit) ? $limit : 10 ?>" data-last-page="1" data-updating="false">
        <thead>
            <tr class="bg-blue-grey-50">
                <?PHP foreach ($modelFields as $key => $settings): ?>
                    <th class="text-uppercase">
                        <?= $settings['label'] ?>
                    </th>
                <?PHP endforeach ?>
                <th class="text-uppercase">
                    Acciones
                </th>
            </tr>
            <tr class="bg-blue-grey-10">
                <?PHP foreach ($modelFields as $key => $settings): ?>
                    <th>
                        <?PHP
                        if (!empty($settings['filter'])):
                            unset($modelFilters[$settings['filter']]['div']);
                            unset($modelFilters[$settings['filter']]['label']);

                            echo $this->Form->input('named.filter.' . $settings['filter'], $modelFilters[$settings['filter']]);
                        endif;
                        ?>
                    </th>
                <?PHP endforeach ?>
                <th style="vertical-align: middle; text-align: center; padding-left: 0px; padding-right: 0px;">


                    <div class="btn-group" role="group">
                        <?PHP foreach ($controllerActions as $controllerAction): ?>

                            <?PHP if (isset($controllerAction['type']) && $controllerAction['type'] == 'submit'): ?>
                                <button data-toggle="tooltip" data-placement="bottom"
                                    data-original-title="<?= isset($controllerAction['title']) ? $controllerAction['title'] : '' ?>"
                                    type="<?= isset($controllerAction['type']) ? $controllerAction['type'] : 'button' ?>"
                                    class="<?= isset($controllerAction['class']) ? $controllerAction['class'] : '' ?>">
                                    <i
                                        class="<?= isset($controllerAction['icon']['class']) ? $controllerAction['icon']['class'] : '' ?>">
                                        <?= isset($controllerAction['icon']['text']) ? $controllerAction['icon']['text'] : '' ?>
                                    </i>
                                </button>
                            <?PHP else: ?>
                                <a href="<?=
                                    Router::url(['action' => $controllerAction['action']] + (isset($controllerAction['controller']) ? ['controller' => $controllerAction['controller']] : []) + (isset($controllerAction['params']) ? $controllerAction['params'] : []))
                                    ?>" data-toggle="tooltip" data-placement="bottom"
                                    data-original-title="<?= isset($controllerAction['title']) ? $controllerAction['title'] : '' ?>"
                                    type="<?= isset($controllerAction['type']) ? $controllerAction['type'] : 'button' ?>"
                                    class="<?= isset($controllerAction['class']) ? $controllerAction['class'] : '' ?>">
                                    <i
                                        class="<?= isset($controllerAction['icon']['class']) ? $controllerAction['icon']['class'] : '' ?>">
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
        <?PHP endif; ?>
        <?PHP foreach ($data as $rowData): ?>
            <tr>
                <?PHP foreach ($modelFields as $key => $settings): ?>
                    <td style="vertical-align: middle;">
                        <?PHP
                        if (isset($settings['render-view'])) {
                            echo $this->element($settings['render-view'], ['data' => $rowData]);
                        } else if (isset($settings['options'])) {
                            echo !empty($settings['options'][Set::extract($settings['bindValue'], $rowData)]) ? $settings['options'][Set::extract($settings['bindValue'], $rowData)] : 'None';
                        } else if (isset($settings['thumbnail']) && !empty($settings['thumbnail'])) {
                            echo $this->Html->image(Set::extract($settings['bindValue'], $rowData), isset($settings['thumbnail']) ? $settings['thumbnail'] : ['width' => '60px', 'height' => '60px', 'style' => 'border-radius:50%;']);
                        } else {
                            $value = Set::extract($settings['bindValue'], $rowData);
                            if (empty($value)) {
                                echo 'Ø';
                            } else {
                                echo $value;
                            }
                        }
                        ?>
                    </td>
                <?PHP endforeach; ?>
                <td class="text-center">
                    <?PHP foreach ($modelActions as $modelAction): ?>
                        <a href=" <?=
                            Router::url((['action' => $modelAction['action']] + (isset($modelAction['controller']) ? ['controller' => $modelAction['controller']] : []) + [$rowData[$modelName]['id']]), true)
                            ?>" data-toggle="tooltip" data-placement="bottom"
                            data-original-title="<?= isset($modelAction['title']) ? $modelAction['title'] : $modelAction['action'] ?>"
                            class="<?= isset($modelAction['class']) ? $modelAction['class'] : '' ?>">
                            <i class="<?= isset($modelAction['icon']['class']) ? $modelAction['icon']['class'] : '' ?>">
                                <?= isset($modelAction['icon']['text']) ? $modelAction['icon']['text'] : '' ?>
                            </i>
                        </a>
                    <?PHP endforeach; ?>
                </td>
            </tr>
        <?PHP endforeach; ?>
        <?PHP if ($this->request->ext != 'ajax'): ?>
        </tbody>
    </table>

    <div class="col-md-2 offset-md-10 d-flex align-items-rigth gap-1 mt-4">
        <div class="paginator">
            <ul class="pagination">
                <?php
                //echo $this->Paginator->prev(__('« Previous'), array('class' => 'page-link'));
                echo $this->Paginator->numbers(
                    array(
                        'separator' => '',
                        'class' => 'pagination-link btn btn-warning waves-effect waves-light btn-block text-white',
                    )
                );
                //echo $this->Paginator->next(__('Next »'), array('class' => 'page-link'));
                ?>
            </ul>
        </div>
    </div>

    <?= $this->Form->end() ?>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            // Get all pagination links
            var paginationLinks = document.querySelectorAll('.pagination .pagination-link');

            // Attach click event listener to each pagination link
            paginationLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent default link behavior

                    // Create a form element
                    var form =  $("#<?= $table_component_id ?>");

                    console.log(form);

                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'data[named][filter][page]');
                    input.setAttribute('value', event.target.text);
                    form.append(input);

                    form.submit();
                });
            });
        });

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
                                    closeOnCancel: true
                                }).then(function () {
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