
        <?php if (!empty($data['Balance']['payment_date']) && $data['Balance']['payment_date'] != '0000-00-00'): ?>
            <?= $data['Balance']['payment_date'] ?>
        <?php else: ?>
            Ø
        <?php endif; ?>