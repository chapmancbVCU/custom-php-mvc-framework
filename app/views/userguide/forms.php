<?php $this->setSiteTitle("Forms - User Guide"); ?>
<?php $this->start('body'); ?>
<?php include(ROOT . DS . 'app' . DS . 'views/layouts/docs_nav.php'); ?>

<div class="main">
    <div class="position-fixed">
        <a href="<?=PROOT?>userguide/index" class="btn btn-xs btn-secondary">User Guide Home</a>
    </div>
    <div class="mb-5 mt-3 my-5 w-75 bg-light mx-auto border rounded p-4">
        Test
    </div>
</div>
<script src="<?=PROOT?>public/js/docNavDropdown.js"></script>
<?php $this->end(); ?>