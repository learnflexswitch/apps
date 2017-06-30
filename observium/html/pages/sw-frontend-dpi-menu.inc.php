<?php

/**
 * Observium Network Management and Monitoring System
 * Copyright (C) 2017-2017, Whistler - http://www.istuary.com
 *
 * @package    observium
 * @subpackage webui
 * @author     York Chen <york.chen@istuary.com>
 * @copyright  (C) 2017-2017 Whistle
 *
 */

$isUserlist = (isset($vars['user_id']) ? true : false);

$navbar['class'] = 'navbar-narrow';
$navbar['brand'] = 'Dpi';

$navbar['options']['dpilist']['url']  = generate_url(array('page' => 'sw-frontend-dpi-rule-list', 'refresh' => 60));
$navbar['options']['dpilist']['text'] = 'All Rules';
$navbar['options']['dpilist']['icon'] = 'oicon-clipboard-eye';
if ($vars['page'] == 'listrules' || $vars['page'] == 'sw-frontend-dpi-rule-list' ) { $navbar['options']['dpilist']['class'] = 'active'; };

if (auth_usermanagement())
{
  $navbar['options']['adddpi']['url']  = generate_url(array('page' => 'sw-frontend-dpi-rule-new', 'refresh' => 0));
  $navbar['options']['adddpi']['text'] = 'Add Rule';
  $navbar['options']['adddpi']['icon'] = 'oicon-clipboard--plus';
  if ($vars['page'] == 'sw-frontend-dpi-rule-new') { $navbar['options']['adddpi']['class'] = 'active'; };


  $navbar['options']['editdpi']['url']  = generate_url(array('page' => 'editrule'));
  $navbar['options']['editdpi']['text'] = 'Import Rules';
  $navbar['options']['editdpi']['icon'] = 'oicon-clipboard--pencil';
  if ($vars['page'] == 'editrule') { $navbar['options']['editdpi']['class'] = 'active'; };
}


/*
if ($isUserlist)
{
  $navbar['options_right']['edit']['url']  = generate_url(array('page' => 'editrule'));
  $navbar['options_right']['edit']['text'] = 'Back to userlist';
  $navbar['options_right']['edit']['icon'] = 'icon-chevron-left';
}
*/

print_navbar($navbar);
unset($navbar);

// EOF
