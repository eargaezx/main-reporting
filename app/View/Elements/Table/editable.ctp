<style>
    .hidden{
        display: none!important;
    }

    table.dataTable td, table.dataTable th {
        border: 1px solid #61666d22!important;
        padding: 4px;
    }
    

</style>

<?PHP $unique_id = uniqid(); ?>
<div class="row" style=" padding-bottom: 20px;">
    <div class="col-sm-12 row" style="font-size: 14px; padding-top: 7px; background-color: #11204a; color: white;
         margin-bottom: -6px;">
        <div class="col-sm-6" style="color: white; ">
            <?= isset($title) ? $title : '' ?>

        </div>
        <div class="col-sm-6 text-right">
            <button id="addToTable-<?= $unique_id ?>" class="btn btn-default btn-sm" style="padding: .15rem .25rem;">
                Add
            </button>
        </div>
    </div>


    <table id="datatable-editable-<?= $unique_id ?>"
           class="form-control-sm horizontal-scroll"
           data-controller = "<?= $controllerName ?>"
           style="<?= count($modelFields) > 12? 'display:block!important':'' ?>"
           >
        <thead>
            <tr class="" style="">
                <?PHP foreach ($modelFields as $key => $settings): ?>
                    <th class="min-width-160" style="<?= (isset($settings['type']) && $settings['type'] == 'hidden') ? 'display:none;' : '' ?> color: #1f283f;">
                        <?= $settings['label'] ?>
                    </th>  
                <?PHP endforeach ?>
                    <th class="actions text-uppercase" width="80px" style="color: #1f283f;">
                    Acciones
                </th>
            </tr>      
        </thead>
        <tbody>
            <?PHP foreach ($data as $rowData): ?>
                <tr>
                    <?PHP foreach ($modelFields as $key => $settings): ?>
                        <td style="vertical-align: middle; <?= (isset($settings['type']) && $settings['type'] == 'hidden') ? 'display:none;' : '' ?>" data-name="<?= $settings['bindValue'] ?>">
                            <?PHP
                            if (isset($settings['render-view'])) {
                                echo $this->element($settings['render-view'], ['data' => $rowData]);
                            } else if (isset($settings['options'])) {
                                echo $settings['options'][Set::extract($settings['bindValue'], $rowData)];
                            } else if (isset($settings['thumbnail']) && !empty($settings['thumbnail'])) {
                                echo $this->Html->image(Set::extract($settings['bindValue'], $rowData), isset($settings['thumbnail']) ? $settings['thumbnail'] : ['width' => '60px', 'height' => '60px', 'style' => 'border-radius:50%;']);
                            } else {
                                $value = Set::extract($settings['bindValue'], $rowData);
                                if (empty($value)) {
                                    echo '';
                                } else {
                                    echo $value;
                                }
                            }
                            ?>
                        </td>
                    <?PHP endforeach; ?>
                    <td class="actions">
                        <?PHP foreach ($modelActions as $modelAction): ?>
                            <a href=" <?=
                            Router::url((['action' => $modelAction['action']] + (isset($modelAction['controller']) ? ['controller' => $modelAction['controller']] : []) + [$rowData[$modelName]['id']]), true)
                            ?>"
                               style="margin-left: 10px;"
                               data-trigger="hover"
                               data-toggle="tooltip" 
                               data-placement="bottom"
                               data-original-title="<?= isset($modelAction['title']) ? $modelAction['title'] : $modelAction['action'] ?>"
                               class="<?= isset($modelAction['class']) ? $modelAction['class'] : '' ?>">
                                <i class="<?= isset($modelAction['icon']['class']) ? $modelAction['icon']['class'] : '' ?>" style="color: red;">
                                    <?= isset($modelAction['icon']['text']) ? $modelAction['icon']['text'] : '' ?>
                                </i>
                            </a>
                        <?PHP endforeach; ?>
                    </td>
                </tr>
            <?PHP endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        /**
         * Theme: Ubold Admin Template
         * Author: Coderthemes
         * Component: Editable
         * 
         */

        (function ($) {

            'use strict';

            var EditableTable = {
                options: {
                    addButton: '#addToTable-<?= $unique_id ?>',
                    table: '#datatable-editable-<?= $unique_id ?>',
                    dialog: {
                        wrapper: '#dialog',
                        cancelButton: '#dialogCancel',
                        confirmButton: '#dialogConfirm',
                    }
                },

                initialize: function () {
                    this
                            .setVars()
                            .build()
                            .events();
                },

                setVars: function () {
                    this.$table = $(this.options.table);
                    this.$addButton = $(this.options.addButton);

                    // dialog
                    this.dialog = {};
                    this.dialog.$wrapper = $(this.options.dialog.wrapper);
                    this.dialog.$cancel = $(this.options.dialog.cancelButton);
                    this.dialog.$confirm = $(this.options.dialog.confirmButton);

                    return this;
                },

                build: function () {
                    this.datatable = this.$table.DataTable({
                        searching: false,
                        paging: false,
                        info: false,
                        sort: false,
                        "language": {
                            "emptyTable": "Sin información disponible"
                        }
                    });

                    window.dt = this.datatable;

                    return this;
                },

                events: function () {
                    var _self = this;



                    this.$table
                            .on('click', 'a.save-row', function (e) {
                                e.preventDefault();

                                $('.tooltip').remove();
                                $('body').tooltip({
                                    selector: '[data-toggle="tooltip"]'
                                });

                                _self.rowSave($(this).closest('tr'));
                            })
                            .on('click', 'a.cancel-row', function (e) {
                                e.preventDefault();
                                $('.tooltip').remove();
                                $('body').tooltip({
                                    selector: '[data-toggle="tooltip"]'
                                });

                                _self.rowCancel($(this).closest('tr'));
                            })
                            .on('click', 'a.edit-row', function (e) {
                                e.preventDefault();
                                $('.tooltip').remove();
                                $('body').tooltip({
                                    selector: '[data-toggle="tooltip"]'
                                });
                                _self.rowEdit($(this).closest('tr'));
                            })
                            .on('click', 'a.remove-row', function (e) {
                                e.preventDefault();

                                var $row = $(this).closest('tr');

                                var _id = $row.find('td:eq(0)').text().trim();
                                _self.deleteData(_id).then(function () {
                                    _self.rowRemove($row);
                                }, function () {
                                    swal(
                                            'Error',
                                            'El registro no pudo ser borrado.',
                                            'error'
                                            );
                                });


                            });

                    this.$addButton.on('click', function (e) {
                        e.preventDefault();

                        _self.rowAdd();
                    });

                    this.dialog.$cancel.on('click', function (e) {
                        e.preventDefault();
                        $.magnificPopup.close();
                    });

                    return this;
                },

                // ==========================================================================================
                // AJAX FUNCTIONS
                // ==========================================================================================               
                saveData: function (data) {
                    var _self = this;

                    return new Promise(function (resolve, reject) {

                        swal({
                            title: 'Guardando',
                            html: 'Por favor espere...',
                            onOpen: function () {
                                swal.showLoading();
                                var url = "<?= Router::url(['controller' => $controllerName, 'action' => 'add.json'], true) ?>";
                                $.ajax({
                                    url: url,
                                    type: 'post',
                                    dataType: 'json',
                                    data: data,
                                    success: function (response) {
                                        if (response && response.hasOwnProperty("success") && response.success == true) {
                                            resolve(response);
                                        } else {
                                            reject(response.message);
                                        }
                                        swal.close();
                                    },
                                    fail: function (jqXHR, textStatus) {
                                        reject(textStatus);
                                        swal.close();
                                    }
                                });

                            },
                            onClose: function () {
                            }
                        });
                    });

                },
                deleteData: function (_id) {
                    var _self = this;

                    return new Promise(function (resolve, reject) {

                        swal({
                            title: 'Borrando',
                            html: 'Por favor espere...',
                            onOpen: function () {
                                swal.showLoading();
                                var url = "<?= Router::url(['controller' => $controllerName, 'action' => 'delete/_ID.json'], true) ?>".replace("_ID", _id);
                                $.ajax({
                                    url: url,
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response && response.hasOwnProperty("success") && response.success == true) {
                                            resolve(response);
                                        } else {
                                            reject(response.message);
                                        }
                                        swal.close();
                                    },
                                    fail: function (jqXHR, textStatus) {
                                        reject(textStatus);
                                        swal.close();
                                    }
                                });

                            },
                            onClose: function () {
                            }
                        });
                    });

                },

                // ==========================================================================================
                // ROW FUNCTIONS
                // ==========================================================================================
                rowAdd: function () {
                    var _self = this;
                    this.$addButton.attr({'disabled': 'disabled'});

                    var actions,
                            data,
                            $row;

                    actions = [
                        '<a href="#" class="hidden on-editing save-row"><i class="fa fa-save"></i></a>',
                        '<a href="#" class="hidden on-editing cancel-row"><i class="fa fa-times"></i></a>',
                        '<a href="#" class="on-default edit-row"><i class="fa fa-pencil"></i></a>',
                        '<a href="#" class="on-default remove-row"><i class="fa fa-trash-o"></i></a>'
                    ].join(' ');

                    data = this.datatable.row.add([
<?PHP foreach ($modelFields as $key => $settings): ?>
                            "<?= isset($settings['default']) ? $settings['default'] : '' ?>",
<?php endforeach; ?>
                        actions]);

                    $row = this.datatable.row(data[0]).nodes().to$();

<?PHP
$i = 0;
foreach ($modelFields as $key => $settings):
    ?>
                        $row.find('td:eq(<?= $i ?>)').attr('data-name', '<?= $key ?>');
    <?php if (isset($settings['type']) && $settings['type'] == 'hidden'): ?>
                            $row.find('td:eq(<?= $i ?>)').css('display', 'none');
    <?php endif; ?>
    <?php
    $i++;
endforeach;
?>


                    $row
                            .addClass('adding')
                            .find('td:last')
                            .addClass('actions');

                    this.rowEdit($row);

                    this.datatable.order([0, 'asc']).draw(); // always show fields
                },

                rowCancel: function ($row) {
                    var _self = this,
                            $actions,
                            i,
                            data;

                    if ($row.hasClass('adding')) {
                        this.rowRemove($row);
                    } else {

                        data = this.datatable.row($row.get(0)).data();
                        this.datatable.row($row.get(0)).data(data);

                        $actions = $row.find('td.actions');
                        if ($actions.get(0)) {
                            this.rowSetActionsDefault($row);
                        }

                        this.datatable.draw();
                    }
                },

                rowEdit: function ($row) {
                    var _self = this,
                            data;

                    data = this.datatable.row($row.get(0)).data();

                    $row.children('td').each(function (i) {
                        var $this = $(this);

                        if ($this.hasClass('actions')) {
                            _self.rowSetActionsEditing($row);
                        } else {
                            $this.html('<input type="text" class="form-control input-block" value="' + data[i] + '"/>');
                        }
                    });
                },

                rowSave: function ($row) {

                    var _self = this,
                            $actions,
                            data = {},
                            values = [];



                    if ($row.hasClass('adding')) {
                        this.$addButton.removeAttr('disabled');
                        $row.removeClass('adding');
                    }

                    $row.find('td').each(function (index) {
                        if (!$(this).hasClass('actions')) {
                            data = leaf(data, $(this).data('name'), ($.trim($(this).find('input').val())));
                        }
                    });


                    _self.saveData(data).then(function (response) {
                        $row.find('td:eq(0)').find('input').val(unleaf($row.find('td:eq(0)').data('name'), response.data));
                        values = $row.find('td').map(function () {
                            var $this = $(this);

                            if ($this.hasClass('actions')) {
                                _self.rowSetActionsDefault($row);
                                return _self.datatable.cell(this).data();
                            } else {
                                return $.trim($this.find('input').first().val());
                            }
                        });

                        _self.datatable.row($row.get(0)).data(values);

                        $actions = $row.find('td.actions');
                        if ($actions.get(0)) {
                            this.rowSetActionsDefault($row);
                        }

                        _self.datatable.draw();
                    }, function (response) {
                        swal(
                                'Error',
                                response,
                                'error'
                                );
                    });


                },

                rowRemove: function ($row) {
                    if ($row.hasClass('adding')) {
                        this.$addButton.removeAttr('disabled');
                    }

                    this.datatable.row($row.get(0)).remove().draw();
                },

                rowSetActionsEditing: function ($row) {
                    $row.find('.on-editing').removeClass('hidden');
                    $row.find('.on-default').addClass('hidden');
                },

                rowSetActionsDefault: function ($row) {
                    $row.find('.on-editing').addClass('hidden');
                    $row.find('.on-default').removeClass('hidden');
                }

            };

            $(function () {
                EditableTable.initialize();
            });

        }).apply(this, [jQuery]);

        function unleaf(path, obj) {
            return path.split('.').reduce(function (a, b) {
                return a && a[b];
            }, obj);
        }

        function leaf(obj, path, value) {
            const pList = path.split('.');
            const key = pList.pop();
            const pointer = pList.reduce((accumulator, currentValue) => {
                if (accumulator[currentValue] === undefined)
                    accumulator[currentValue] = {};
                return accumulator[currentValue];
            }, obj);
            pointer[key] = value;
            return obj;
        }
    });
</script>

