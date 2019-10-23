
    <?php if(!empty($comment['children'])):?>
        <ul class="parent">
            <?= $this->getComments($comment['children']) ?>
        </ul>
    <?php endif; ?>
</li>