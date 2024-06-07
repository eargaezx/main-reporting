<?php
if (!isset($data) || (!is_array($data) && empty($data))) {//no empty value
    $data = [
        'success' => FALSE,
        'message' => empty($this->Session->read('Message'))? 'La operación no pudo ser realizada.' : trim(implode(' ', Hash::extract($this->Session->read('Message'), '{s}.{n}.message'))),
        'data' => []
    ];
} else if (!isset($data['success']) || !isset($data['message']) || !isset($data['data'])) {
    $data = [
        'success' => TRUE,
        'message' => empty($this->Session->read('Message'))? 'Operación realizada correctamente' : trim(implode(' ', Hash::extract($this->Session->read('Message'), '{s}.{n}.message'))),
        'data' => $data,
    ];
}
CakeSession::delete('Message');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
