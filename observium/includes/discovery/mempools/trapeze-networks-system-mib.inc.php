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

//  TRAPEZE-NETWORKS-SYSTEM-MIB::trpzSysCpuMemorySize.0
//  TRAPEZE-NETWORKS-SYSTEM-MIB::trpzSysCpuMemoryLast5MinutesUsage.0

$descr  = 'Memory';
$used   = snmp_get($device, 'trpzSysCpuMemoryLast5MinutesUsage.0', '-OQUvs', $mib);
$total  = snmp_get($device, 'trpzSysCpuMemorySize.0',              '-OQUvs', $mib);
//$used  *= 1024;
//$total *= 1024;

if (is_numeric($used) && is_numeric($total))
{
  discover_mempool($valid['mempool'], $device, 0, 'TRAPEZE-NETWORKS-SYSTEM-MIB', $descr, 1024, $total, $used);
}

unset ($descr, $total, $used);

// EOF