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

if ($_SESSION['userlevel'] < 10)
{
  print_error_permission();
  return;
}

include($config['html_dir']."/pages/sw-frontend-dpi-menu.inc.php");
register_html_title("All Rules");

$userlist = array();
?>

<link href="css/sw-frontend-dpi-rule-new.css" rel="stylesheet" type="text/css" />

<div ng-app="myApp">
  <div ng-controller="dpiRulesListController as demo">
    <table ng-table="tableParams" class="table table-condensed table-bordered table-striped" show-filter="true">
      <tr ng-repeat="row in $data">
        <td data-title="'Sid'"  filter="{ sid: 'text'}" sortable="'sid'"><a target="_self"  ng-attr-title="{{row.options}}"  ng-href="/sw-frontend-dpi-rule-list/id={{row.sid}}/">{{row.sid}}</a></td>
        <td data-title="'Category'" filter="{ file: 'select'}" filter-data="categories" sortable="'file'">{{row.file}}</td>
	<td data-title="'Protocol'" filter="{ protocol: 'select'}" filter-data="protocols" sortable="'protocol'">{{row.protocol}}</td>
        <td data-title="'SrcIP'" filter="{ source_ip: 'text'}"  sortable="'source_ip'">{{row.source_ip}}</td>
        <td data-title="'SrcPort'" filter="{ source_port: 'text'}" sortable="'source_port'">{{row.source_port}}</td>
        <td data-title="'DestIP'" filter="{ destination_ip: 'text'}" sortable="'destination_ip'">{{row.destination_ip}}</td>
        <td data-title="'DestPort'" filter="{ destination_port: 'text'}" sortable="'destination_port'">{{row.destination_port}}</td>
        <td data-title=""  > 
           <button class="btn btn-s" ng-click="deleteEntity(row) " title="Delete Rule">
               <i class="glyphicon glyphicon-trash"></i>
           </button>
        </td>

      </tr>
    </table>
  </div>
</div>


<script src="js/angular-cookies.min.js"></script>
<script src="js/sw-frontend-dpi-rule-new.js"></script>
<script src="js/sw-frontend-dpi-rule-list.js"></script>

<?php
// EOF

