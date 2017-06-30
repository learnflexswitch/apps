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

// EMBEDDED-NGX-MIB::swMemRamFree.0 = INTEGER: 24328
// EMBEDDED-NGX-MIB::swMemRamTotal.0 = INTEGER: 49380

$descr  = 'Memory';
$free   = snmp_get($device, 'swMemRamFree.0',  '-OQUvs', $mib);
$total  = snmp_get($device, 'swMemRamTotal.0', '-OQUvs', $mib);

if (is_numeric($free) && is_numeric($total))
{
  //$free  *= 1024;
  //$total *= 1024;
  $used   = $total - $free;
  discover_mempool($valid['mempool'], $device, 0, 'EMBEDDED-NGX-MIB', $descr, 1024, $total, $used);
}

unset ($descr, $total, $free, $used);

// EOF