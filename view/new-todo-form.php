<form action="index.php" method="post" class="row row-cols-lg-auto py-4">
    <div class="col-12">
        <input type="text" class="form-control" name="text" value="<?php echo htmlspecialchars($updateText ?? ''); ?>">
        <?php if (!empty($updateId)): ?>
            <input type="hidden" name="update_id" value="<?php echo $updateId; ?>">
        <?php endif; ?>
    </div>
    <button type="submit" class="col-12 btn btn-primary"><?php echo empty($updateId) ? '+' : 'Update'; ?></button>
</form>
