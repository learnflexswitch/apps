<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2017-2017, Whistler - http://www.istuary.com
 *
 * @package    observium
 * @subpackage webui
 * @author     York Chen <york.chen@istuary.com>
 * @copyright  (C) 2017-2017 Whistler
 *
 */

?>
<div class="row">
<div class="col-md-12">

<?php

$vars = get_vars();
//print_r($_COOKIE);
//print_r($_SESSION['username']);

//print( $_COOKIE['ckey']);//generate_url($vars));

// Note, this form have more complex grid and class elements for responsive datetime field
$form = array('type'          => 'rows',
              'space'         => '5px',
              'submit_by_key' => FALSE,
              'url'           => generate_url($vars));

$where = ' WHERE 1 ' . $cache['where']['devices_permitted'];

$form_items['devices'] = generate_form_values('whistler_switch');
//print_r ($form_items['devices']);
$form_items['rules'] = generate_form_values('dpi_rules');

//print_r($form_items['devices']);
//print_r($form_items['rules']);

// Rule file ID field
$form['row'][0]['rule_file_id'] = array(
                              'type'        => 'multiselect',
                              'name'        => 'Switchs',
                              'width'       => '100%',
                              'div_class'   => 'col-lg-1 col-md-2 col-sm-2',
                              'value'       => $vars['rule_file_id'],
                              'groups'      => array('', 'UP', 'DOWN', 'DISABLED'), // This is optgroup order for values (if required)
                              'values'      => $form_items['devices']);

// Add device_id limit for other fields
if (isset($vars['switch_id']))
{
  $where .= generate_query_values($vars['device_id'], 'device_id');
}

// Rules field
/*
$form['row'][0]['rules'] = array(
                              'type'        => 'multiselect',
                              'name'        => 'Rules',
                              'placeholder' => 'Rules',
                              'width'       => '100%',
                              'div_class'   => 'col-lg-3 col-md-4 col-sm-4',
                              //'grid'        => 7,
                              'value'       => $form_items['rules']);//$vars['rules']);
*/

$form['row'][0]['rules'] = array(
                              'type'        => 'multiselect',
                              'name'        => 'Rules',
                              'width'       => '100%',
                              'div_class'   => 'col-lg-1 col-md-2 col-sm-2',
                              'value'       => '',
                              'groups'      => array('', 'ENABLED'), 
                              'values'      => $form_items['rules']);


// apply button
$form['row'][0]['apply']   = array(
                              'type'        => 'submit',
                              'name'        => 'Apply',
                              'icon'        => 'oicon-network-hub',
                              'div_class'   => 'col-lg-1 col-md-5 col-sm-2',
                              //'grid'        => 1,
                              'right'       => TRUE);

print_form($form);
unset($form, $form_items, $form_devices);


// Pagination
$vars['pagination'] = TRUE;

// Print events
//print_events($vars);

register_html_title('Apply Rules');

?>

  </div> <!-- col-md-12 -->

</div> <!-- row -->

<?php

// EOF
