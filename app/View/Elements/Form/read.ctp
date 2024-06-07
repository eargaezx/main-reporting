<table class="table table table-bordered table-striped">
    <thead>
    </thead>
    <tbody>
        <?php foreach ($modelFields as $key => $settings): ?>
            <tr>
                <td class="font-weight-bold px-4" style="width:1%; white-space:nowrap;">
                    <?= $settings['label'] ?>
                </td>
                <td class="font-weight-normal">

                    <?PHP
                    if (isset($settings['render-view'])) {
                        echo $this->element($settings['render-view'], ['data' => $this->request->data]);
                    } else if (isset($settings['options'])) {
                        echo $settings['options'][Set::extract($settings['bindValue'], $this->request->data)];
                    } else if (isset($settings['thumbnail']) && !empty($settings['thumbnail'])) {
                        echo $this->Html->image(Set::extract($settings['bindValue'], $this->request->data), isset($settings['thumbnail']) ? $settings['thumbnail'] : ['width' => '60px', 'height' => '60px', 'style' => 'border-radius:50%;']);
                    } else {
                        $value = Set::extract($settings['bindValue'], $this->request->data);
                        if (empty($value)) {
                            echo 'NO DEFINIDO';
                        } else {
                            echo $value;
                        }
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>


    </tbody>
</table>