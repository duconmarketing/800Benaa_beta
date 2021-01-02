<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php $view->inc('elements/header.php'); ?>
<div class="full">
<div class="container">
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
<?php
View::element(
    'system_errors',
    array(
        'format' => 'block',
        'error' => isset($error) ? $error : null,
        'success' => isset($success) ? $success : null,
        'message' => isset($message) ? $message : null,
    )
);
?>
</div>
</div>

<div class="in_content">
<?php print $innerContent ?>
</div>

</div>
</div>

<?php $view->inc('elements/footer.php'); ?>