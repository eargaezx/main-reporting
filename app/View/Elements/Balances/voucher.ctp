
<div class="row" style="text-align: center">
    <div class="col-sm-12">
        <?php if (!empty($data['Balance']['voucher'])): ?>
        <a  target="_blank" href="<?= $data['Balance']['voucher'] ?>"> <i class="fe-paperclip"></i></a>
        <?php else: ?>
            Ø
        <?php endif; ?>
    </div>
</div>