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

//ACD-DESC-MIB::acdDescCommercialName.0 = STRING: AMO-10000-NE
//ACD-DESC-MIB::acdDescMacBaseAddr.0 = STRING: 30:30:3a:31:35:3a:41:44:3a:30:38:3a:45:46:3a:35:38
//ACD-DESC-MIB::acdDescIdentifier.0 = STRING: G080-0157
//ACD-DESC-MIB::acdDescFirmwareVersion.0 = STRING: AMO_10GE_5.3.1.1_23046
//ACD-DESC-MIB::acdDescHardwareVersion.0 = STRING: 500-018-03:9:16
//ACD-DESC-MIB::acdDescSerialNumber.0 = STRING: G080-0157

$data = snmpget_cache_multi($device, 'acdDescCommercialName.0 acdDescFirmwareVersion.0 acdDescSerialNumber.0', array(), 'ACD-DESC-MIB');
$hardware   = $data[0]['acdDescCommercialName'];
$serial     = $data[0]['acdDescSerialNumber'];
$version    = $data[0]['acdDescFirmwareVersion'];
if (preg_match('/^(\w[^_\W]+_)*(?<version>[\d\.\-]+)/', $version, $matches))
{
  $version = $matches['version'];
}

// EOF