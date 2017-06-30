<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Parse CIMC version number from sysDescr
if (preg_match('/Firmware Version ([^,]+) Copyright/', $poll_device['sysDescr'], $matches))
{
  $version = $matches[1];
}

$serial   = snmp_get($device, 'cucsComputeBoardSerial.1',  '-Oqv', 'CISCO-UNIFIED-COMPUTING-COMPUTE-MIB');
$hardware = snmp_get($device, 'cucsComputeRackUnitName.1', '-Oqv', 'CISCO-UNIFIED-COMPUTING-COMPUTE-MIB');

$cimc_separate   = array();
$cimc_separate[] = ".1.3.6.1.4.1.9.1.1634"; // ciscoE160DP
$cimc_separate[] = ".1.3.6.1.4.1.9.1.1635"; // ciscoE160D
$cimc_separate[] = ".1.3.6.1.4.1.9.1.1636"; // ciscoE140DP
$cimc_separate[] = ".1.3.6.1.4.1.9.1.1637"; // ciscoE140D
$cimc_separate[] = ".1.3.6.1.4.1.9.1.1638"; // ciscoE140S
$cimc_separate[] = ".1.3.6.1.4.1.9.1.2183"; // ciscoUCSE160DM2K9
$cimc_separate[] = ".1.3.6.1.4.1.9.1.2184"; // ciscoUCSE180DM2K9

if (in_array($poll_device['sysObjectID'], $cimc_separate))
{
  $type = 'management';
}

unset($cimc_separate, $matches);

// EOF
