<?PHP
//echo pr(array_keys($modelFilters));
//echo pr(array_keys($modelFields));
//echo pr($modelFilters);
//echo pr($modelFields);
if ($this->request->ext != 'ajax'):
    //echo pr($modelFields); die();
    $table_component_id = CakeText::uuid();
    ?>
    <div id="<?= $table_component_id ?>">
        <div class="table-container">

            <table class="table table-sm table-striped table-bordered table-actions-bar "
                data-table-action="<?= Router::url(['controller' => $controllerName, 'action' => $actionName, ''], true) ?>"
                data-paginate-limit="<?= isset($limit) ? $limit : 10 ?>" data-last-page="1" data-updating="false">
                <thead>
                    <tr class="bg-blue-grey-50">
                        <?PHP foreach ($modelFields as $key => $settings): ?>
                            <th class="text-uppercase">
                                <?= $settings['label'] ?>
                            </th>
                        <?PHP endforeach ?>
                        <th class="text-uppercase fixed-column" style="background-color: #eceef0!important;">
                            Acciones
                        </th>
                    </tr>
                    <tr class="bg-blue-grey-10">
                        <?PHP

                        echo $this->Form->create('filter', [
                            'id' => $table_component_id . '-form-filter',
                            'class' => 'form-horizontal match-height table-responsive ',
                            'inputDefaults' => [
                                'label' => FALSE,
                                'class' => 'form-control',
                                'fieldset' => FALSE,
                                'format' => FALSE,
                                'div' => FALSE
                            ]
                        ])
                            ?>
                        <?PHP foreach ($modelFields as $key => $settings): ?>
                            <th>
                                <?PHP
                                if (!empty($settings['filter'])):
                                    unset($modelFilters[$settings['filter']]['div']);
                                    unset($modelFilters[$settings['filter']]['label']);
                                    unset($modelFilters[$key]);
                                    echo $this->Form->input('named.filter.' . $settings['filter'], $modelFilters[$settings['filter']]);
                                endif;
                                ?>
                            </th>
                        <?PHP endforeach ?>
                        <?PHP
                        foreach ($modelFilters as $key => $settings):
                            echo $this->Form->hidden('named.filter.' . $key);
                        endforeach ?>
                        <?= $this->Form->end(); ?>
                        <th class="fixed-column"
                            style="background-color: #eceef0!important; vertical-align: middle; text-align: center; padding-left: 20px; padding-right: 20px;">


                            <div class="btn-group" role="group">
                                <?PHP foreach ($controllerActions as $controllerAction): ?>

                                    <?PHP if (isset($controllerAction['type']) && $controllerAction['type'] == 'search'): ?>
                                        <button data-toggle="tooltip" data-placement="bottom"
                                            data-original-title="<?= isset($controllerAction['title']) ? $controllerAction['title'] : '' ?>"
                                            type="<?= isset($controllerAction['type']) ? $controllerAction['type'] : 'button' ?>"
                                            class="<?= isset($controllerAction['class']) ? $controllerAction['class'] : '' ?>">
                                            <i
                                                class="<?= isset($controllerAction['icon']['class']) ? $controllerAction['icon']['class'] : '' ?>">
                                                <?= isset($controllerAction['icon']['text']) ? $controllerAction['icon']['text'] : '' ?>
                                            </i>
                                        </button>
                                        <?PHP
                                    elseif (isset($controllerAction['type']) && $controllerAction['type'] == 'post'):
                                        echo $this->Form->postLink(
                                            '<i class="' . (isset($controllerAction['icon']['class']) ? $controllerAction['icon']['class'] : '') . '"></i>',
                                            [
                                                'controller' => isset($controllerAction['controller']) ? isset($controllerAction['controller']) : $controllerName,
                                                'action' => $controllerAction['action']
                                            ],
                                            [
                                                'data' => $controllerAction['data'],
                                                'escape' => false, // Permitir HTML dentro del enlace
                                                'confirm' => false,
                                                'class' => isset($controllerAction['class']) ? $controllerAction['class'] : '' // Agregar clase CSS
                                            ]
                                        );
                                        ?>
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
                        <td class="text-center fixed-column" style="background-color: #eceef0!important;">
                            <?PHP
                            foreach ($modelActions as $modelAction):
                                echo $this->Form->postLink(
                                    '<i class="' . (isset($modelAction['icon']['class']) ? $modelAction['icon']['class'] : '') . '">' . (isset($modelAction['icon']['text']) ? $modelAction['icon']['text'] : '') . '</i>',
                                    [
                                        'controller' => (isset($modelAction['controller']) ? ['controller' => $modelAction['controller']] : $controllerName),
                                        'action' => $modelAction['action'],
                                        $rowData[$modelName]['id']
                                    ],
                                    [
                                        'method' => 'GET',
                                        'data' => !empty($modelAction['data']) ? $modelAction['data'] : [],
                                        'escape' => false, // Permitir HTML dentro del enlace
                                        'confirm' => false,
                                        'class' => isset($modelAction['class']) ? $modelAction['class'] : '' // Agregar clase CSS
                                    ]
                                );
                            endforeach;
                            ?>
                        </td>
                    </tr>
                <?PHP endforeach; ?>
                <?PHP if ($this->request->ext != 'ajax'): ?>
                </tbody>
            </table>
        </div>
    </div>
<?PHP endif; ?>

<?PHP if ($this->request->ext != 'ajax'): ?>
    <div class="col-md-2 offset-md-10 d-flex align-items-rigth gap-1 mt-4">
        <div class="paginator" id="<?= $table_component_id ?>-paginator">
        <?PHP endif; ?>
        <ul class="pagination">
            <?php
            //echo $this->Paginator->prev(__('« Previous'), array('class' => 'page-link'));
            echo $this->Paginator->numbers(
                array(
                    'separator' => '',
                    'class' => 'pagination-link btn btn-warning waves-effect waves-light btn-block',
                )
            );
            //echo $this->Paginator->next(__('Next »'), array('class' => 'page-link'));
            ?>
        </ul>

        <?PHP if ($this->request->ext != 'ajax'): ?>
        </div>
    </div>
<?PHP endif; ?>

<?PHP if ($this->request->ext != 'ajax'): ?>


    <script>


        document.addEventListener('DOMContentLoaded', function () {
            return;
            // Get all pagination links
            var paginationLinks = document.querySelectorAll('.pagination .pagination-link');

            // Attach click event listener to each pagination link
            paginationLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent default link behavior

                    // Create a form element
                    var form = $("#<?= $table_component_id ?>");
                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'data[named][filter][page]');
                    input.setAttribute('value', event.target.text);
                    form.append(input);

                    form.submit();

                    event.target.text

                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {

            let buttonSearch = $("#<?= $table_component_id ?>").find('button[type="search"]').first();

            buttonSearch.click(function () {
                event.preventDefault(); // Prevent default link behavior
                let table = $("#<?= $table_component_id ?>").find("table").first();
                let action = $(table).data("table-action") + '.action';
                var form = $("#<?= $table_component_id ?>-form-filter");

                var input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', 'data[named][filter][page]');
                input.setAttribute('value', 1);
                form.append(input);

                // Crear un objeto FormData y filtrar los campos vacíos
                var formData = $(form).serializeArray();
                var filteredData = formData.filter(function (item) {
                    return item.value.trim() !== "";
                });

                // Convertir los datos filtrados de nuevo a una cadena de consulta
                var queryString = $.param(filteredData);


                $.ajax({
                    url: action,
                    type: 'POST',
                    data: queryString,
                    success: function (response) {
                        // Crea un contenedor temporal para manipular la respuesta
                        var tempContainer = $('<div></div>').html('<table>' + response + '</table>');
                        let pagination = $(tempContainer).find('.pagination');

                        // Inserta el contenido modificado en el documento HTML
                        $("#<?= $table_component_id ?>-paginator").find('.pagination').first().html(pagination);


                        $(table).find('tbody').html(response);

                        runs();
                    }
                });

            });

            function runs() {
                // Get all pagination links
                var paginationLinks = document.querySelectorAll('.pagination .pagination-link');
                let table = $("#<?= $table_component_id ?>").find("table").first();
                // Attach click event listener to each pagination link
                paginationLinks.forEach(function (link) {
                    link.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent default link behavior
                        let action = $(table).data("table-action") + '.action';
                        // Create a form element
                        var form = $("#<?= $table_component_id ?>-form-filter");


                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'data[named][filter][page]');
                        input.setAttribute('value', event.target.text);
                        form.append(input);

                        //form.submit();

                        $.ajax({
                            url: action,
                            type: 'POST',
                            data: form.serialize(),
                            success: function (response) {
                                // Crea un contenedor temporal para manipular la respuesta
                                var tempContainer = $('<div></div>').html('<table>' + response + '</table>');
                                //console.log(tempContainer.html());
                                var pagination = $(tempContainer).find('.pagination');
                                console.log(pagination);

                                // Inserta el contenido modificado en el documento HTML

                                $("#<?= $table_component_id ?>-paginator").find('.pagination').first().html(pagination);

                                $(table).find('tbody').html(response).find('.swal-confirm').click(function (e) {
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

                                runs();

                                $(table).trigger("update");
                                //var count = $(self.table).find("tbody").find("tr").length;
                                //$(self).find('.counter').first().text(count);
                                $(table).data('updating', false);
                            }
                        });

                    });
                });
            }

            runs();
        });

        document.addEventListener('DOMContentLoaded', function () {
            jQuery(document).ready(function () {
                return;
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