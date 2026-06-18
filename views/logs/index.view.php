<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding" style="background: white;">
    <?php getTables($db); ?>
    <div id="tooltip" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; z-index:1000;"></div>
</div>

<script>
    window.db_listFK = <?= json_encode($LISTFK) ?>;
    window.db_listMetadatas = <?= json_encode($LISTMETADATAS) ?>;
</script>
<script src="js/logs.js"></script>

<?php
include "$root/inc/footer.php";
?>
