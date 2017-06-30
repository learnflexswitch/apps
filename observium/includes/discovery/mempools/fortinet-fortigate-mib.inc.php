<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$percent = snmp_get($device, 'fgSysMemUsage.0', '-OvQ', $mib);
$total   = snmp_get($device, 'fgSysMemCapacity.0', '-OvQ', $mib);
$used    = $total * $percent / 100;

if (is_numeric($percent) && is_numeric($total))
{
  discover_mempool($valid['mempool'], $device, 0, 'FORTINET-FORTIGATE-MIB', 'Memory', 1, $total, $used);
}

unset ($total, $used, $percent);

// EOF