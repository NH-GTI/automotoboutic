<?php
if (!defined('STORE_COMMANDER')) { exit; }
?>
<script  src="<?php echo SC_JQUERY; ?>"></script>

<div class="configuring" style="cursor: pointer; text-align: center;">
    <img src="lib/img/configuring.png?1" alt="" width="150px" />
    <br/> <br/>
</div>
<button class="btn center big configuring"><?php echo _l('Configure this project'); ?></button>

<script>
    $( ".configuring" ).on( "click", function() {
        $.post('index.php?ajax=1&act=all_fizz_win-project_update',
            {
                'id_project': "<?php echo $id_project; ?>",
                'action': 'configuring'
            },
            function(data){
                parent.ESPloadConfig();
            });
    });

</script>