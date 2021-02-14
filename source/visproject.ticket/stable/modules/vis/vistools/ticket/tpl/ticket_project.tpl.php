<?php if(($ticket_data!=array())&&($view=='ticket')):?>

<?php include(vOut('settings_abspath').'modules/'.vOut('frame_current_module').'/vistools/'.osW_VIS::getInstance()->getTool().'/tpl/ticket_core_details.tpl.php'); ?>

<?php endif?>

<?php echo osW_DDM3::getInstance()->runDDMTPL($ddm_group)?>