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

/// We should walk, so we can discover here too.

$table_rows = array();
$c_table_rows = array();

// 'BGP4-MIB', 'CISCO-BGP4-MIB', 'BGP4-V2-MIB-JUNIPER', 'FORCE10-BGP4-V2-MIB', 'ARISTA-BGP4V2-MIB'
if ($config['enable_bgp'] && is_device_mib($device, 'BGP4-MIB')) // Note, BGP4-MIB is main MIB, without it, the rest will not be checked
{
  $p_list          = array(); // Init founded peers list
  $force_discovery = FALSE;   // Flag for force or not rediscover bgp peers

  // Get Local ASN
  $bgpLocalAs = snmp_get($device, 'bgpLocalAs.0', '-OUQvn', 'BGP4-MIB');

  $bgp_oids  = array('bgpPeerState', 'bgpPeerAdminStatus', 'bgpPeerInUpdates', 'bgpPeerOutUpdates',
                     'bgpPeerInTotalMessages', 'bgpPeerOutTotalMessages', 'bgpPeerFsmEstablishedTime',
                     'bgpPeerInUpdateElapsedTime'); // , 'bgpPeerLocalAddr', 'bgpPeerIdentifier');
  $cbgp_oids = array('cbgpPeerAcceptedPrefixes', 'cbgpPeerDeniedPrefixes', 'cbgpPeerPrefixAdminLimit',
                     'cbgpPeerPrefixThreshold', 'cbgpPeerPrefixClearThreshold', 'cbgpPeerAdvertisedPrefixes',
                     'cbgpPeerSuppressedPrefixes', 'cbgpPeerWithdrawnPrefixes');
  $vendor_oids = array(
    // Juniper BGP4-V2 MIB
    'BGP4-V2-MIB-JUNIPER' => array('vendor_use_index'         => array('jnxBgpM2PeerRemoteAddr'     => 1,
                                                                       'jnxBgpM2PeerRemoteAddrType' => 1,
                                                                       'jnxBgpM2PeerLocalAddr'      => 1),
                                   'vendor_PeerTable'               => 'jnxBgpM2PeerTable',
                                   'vendor_PeerState'               => 'jnxBgpM2PeerState',
                                   'vendor_PeerAdminStatus'         => 'jnxBgpM2PeerStatus',                //'jnxBgpM2CfgPeerAdminStatus' not exist in JunOS
                                   'vendor_PeerInUpdates'           => 'jnxBgpM2PeerInUpdates',
                                   'vendor_PeerOutUpdates'          => 'jnxBgpM2PeerOutUpdates',
                                   'vendor_PeerInTotalMessages'     => 'jnxBgpM2PeerInTotalMessages',
                                   'vendor_PeerOutTotalMessages'    => 'jnxBgpM2PeerOutTotalMessages',
                                   'vendor_PeerFsmEstablishedTime'  => 'jnxBgpM2PeerFsmEstablishedTime',
                                   'vendor_PeerInUpdateElapsedTime' => 'jnxBgpM2PeerInUpdatesElapsedTime',
                                   'vendor_PeerLocalAs'             => 'jnxBgpM2PeerLocalAs',
                                   'vendor_PeerLocalAddr'           => 'jnxBgpM2PeerLocalAddr',
                                   'vendor_PeerIdentifier'          => 'jnxBgpM2PeerIdentifier',
                                   'vendor_PeerRemoteAs'            => 'jnxBgpM2PeerRemoteAs',
                                   'vendor_PeerRemoteAddr'          => 'jnxBgpM2PeerRemoteAddr',
                                   'vendor_PeerRemoteAddrType'      => 'jnxBgpM2PeerRemoteAddrType',
                                   'vendor_PeerIndex'               => 'jnxBgpM2PeerIndex',
                                   'vendor_PeerAcceptedPrefixes'    => 'jnxBgpM2PrefixInPrefixesAccepted',
                                   'vendor_PeerDeniedPrefixes'      => 'jnxBgpM2PrefixInPrefixesRejected',
                                   'vendor_PeerAdvertisedPrefixes'  => 'jnxBgpM2PrefixOutPrefixes',
                                   'vendor_PrefixCountersSafi'      => 'jnxBgpM2PrefixCountersSafi'),
    // Force10 BGP4-V2 MIB
    'FORCE10-BGP4-V2-MIB' => array('vendor_PeerTable'               => 'f10BgpM2PeerTable',
                                   'vendor_PeerState'               => 'f10BgpM2PeerState',
                                   'vendor_PeerAdminStatus'         => 'f10BgpM2PeerStatus',
                                   'vendor_PeerInUpdates'           => 'f10BgpM2PeerInUpdates',
                                   'vendor_PeerOutUpdates'          => 'f10BgpM2PeerOutUpdates',
                                   'vendor_PeerInTotalMessages'     => 'f10BgpM2PeerInTotalMessages',
                                   'vendor_PeerOutTotalMessages'    => 'f10BgpM2PeerOutTotalMessages',
                                   'vendor_PeerFsmEstablishedTime'  => 'f10BgpM2PeerFsmEstablishedTime',
                                   'vendor_PeerInUpdateElapsedTime' => 'f10BgpM2PeerInUpdatesElapsedTime',
                                   'vendor_PeerLocalAs'             => 'f10BgpM2PeerLocalAs',
                                   'vendor_PeerLocalAddr'           => 'f10BgpM2PeerLocalAddr',
                                   'vendor_PeerIdentifier'          => 'f10BgpM2PeerIdentifier',
                                   'vendor_PeerRemoteAs'            => 'f10BgpM2PeerRemoteAs',
                                   'vendor_PeerRemoteAddr'          => 'f10BgpM2PeerRemoteAddr',
                                   'vendor_PeerRemoteAddrType'      => 'f10BgpM2PeerRemoteAddrType',
                                   'vendor_PeerIndex'               => 'f10BgpM2PeerIndex',
                                   'vendor_PeerAcceptedPrefixes'    => 'f10BgpM2PrefixInPrefixesAccepted',
                                   'vendor_PeerDeniedPrefixes'      => 'f10BgpM2PrefixInPrefixesRejected',
                                   'vendor_PeerAdvertisedPrefixes'  => 'f10BgpM2PrefixOutPrefixes',
                                   'vendor_PrefixCountersSafi'      => 'f10BgpM2PrefixCountersSafi'),
    // Arista BGP4-V2 MIB
    'ARISTA-BGP4V2-MIB'   => array('vendor_use_index'         => array('aristaBgp4V2PeerRemoteAddr'      => 1,
                                                                       'aristaBgp4V2PeerRemoteAddrType'  => 1),
                                   'vendor_PeerTable'               => 'aristaBgp4V2PeerTable',
                                   'vendor_PeerState'               => 'aristaBgp4V2PeerState',
                                   'vendor_PeerAdminStatus'         => 'aristaBgp4V2PeerAdminStatus',
                                   'vendor_PeerInUpdates'           => 'aristaBgp4V2PeerInUpdates',
                                   'vendor_PeerOutUpdates'          => 'aristaBgp4V2PeerOutUpdates',
                                   'vendor_PeerInTotalMessages'     => 'aristaBgp4V2PeerInTotalMessages',
                                   'vendor_PeerOutTotalMessages'    => 'aristaBgp4V2PeerOutTotalMessages',
                                   'vendor_PeerFsmEstablishedTime'  => 'aristaBgp4V2PeerFsmEstablishedTime',
                                   'vendor_PeerInUpdateElapsedTime' => 'aristaBgp4V2PeerInUpdatesElapsedTime',
                                   'vendor_PeerLocalAs'             => 'aristaBgp4V2PeerLocalAs',
                                   'vendor_PeerLocalAddr'           => 'aristaBgp4V2PeerLocalAddr',
                                   'vendor_PeerIdentifier'          => 'aristaBgp4V2PeerRemoteIdentifier',
                                   'vendor_PeerRemoteAs'            => 'aristaBgp4V2PeerRemoteAs',
                                   'vendor_PeerRemoteAddr'          => 'aristaBgp4V2PeerRemoteAddr',
                                   'vendor_PeerRemoteAddrType'      => 'aristaBgp4V2PeerRemoteAddrType',
                                   'vendor_PeerIndex'               => '',
                                   'vendor_PeerAcceptedPrefixes'    => 'aristaBgp4V2PrefixInPrefixesAccepted',
                                   'vendor_PeerDeniedPrefixes'      => '',
                                   'vendor_PeerAdvertisedPrefixes'  => 'aristaBgp4V2PrefixOutPrefixes',
                                   'vendor_PrefixCountersSafi'      => 'aristaBgp4V2PrefixInPrefixes'),
                                   # PrefixCountersSafi is not-accessible in draft-13, but we
                                   # only use the INDEX from it, so use aristaBgp4V2PrefixInPrefixes.
  );

  $vendor_mib = FALSE;
  foreach ($vendor_oids as $v_mib => $v_array)
  {
    if (is_device_mib($device, $v_mib))
    {
      $vendor_mib = $v_mib; // Set to current vendor mib
      foreach ($v_array as $v => $val) { $$v = $val; }

      if ($v_mib === 'BGP4-V2-MIB-JUNIPER' && $bgpLocalAs === '0')
      {
        // On JunOS BGP4-MIB::bgpLocalAs.0 is always '0'.
        $v_bgpLocalAs = trim(snmp_walk($device, 'jnxBgpM2PeerLocalAs', '-OUQvn', 'BGP4-V2-MIB-JUNIPER'));
        list($bgpLocalAs) = explode("\n", $v_bgpLocalAs);
      }
      break;
    }
  }

  // Some Old IOS-XR (ie 4.3.2) also return BGP4-MIB::bgpLocalAs.0 as '0'.
  if ($vendor_mib === FALSE && $bgpLocalAs === '0' && is_device_mib($device, 'CISCO-BGP4-MIB'))
  {
    $v_bgpLocalAs = snmp_get($device, 'cbgpLocalAs.0', '-OUQvn', 'CISCO-BGP4-MIB');
    if (is_numeric($v_bgpLocalAs))
    {
      $bgpLocalAs = $v_bgpLocalAs;
    }
  }

  if (is_numeric($bgpLocalAs) && $bgpLocalAs != '0')
  {
    $bgpLocalAs = snmp_dewrap32bit($bgpLocalAs); // Dewrap for 32bit ASN
    print_cli_data("Local AS", "AS$bgpLocalAs ", 2);

    $cisco_version   = FALSE;
    if (is_device_mib($device, 'CISCO-BGP4-MIB'))
    {
      $cisco_version = 1;
      // Check Cisco cbgpPeer2Table first
      $cisco_peers   = snmpwalk_cache_oid($device, 'cbgpPeer2RemoteAs', array(), 'CISCO-BGP4-MIB');
      if (count($cisco_peers) > 0)
      {
        $cisco_version = 2;
      }
    }

    // Cache data
    print_cli_data_field("Caching", 2);
    if ($cisco_version === 2)
    {
      echo("CISCO-BGP4-MIB ");
      foreach ($bgp_oids as $bgp_oid)
      {
        $c_oid = str_replace(array('bgpPeer', 'Identifier'), array('cbgpPeer2', 'RemoteIdentifier'), $bgp_oid);
        $cisco_peers = snmpwalk_cache_oid($device, $c_oid, $cisco_peers, 'CISCO-BGP4-MIB');
      }

      // Collect founded peers
      foreach ($cisco_peers as $peer_ip => $entry)
      {
        list(,$peer_ip) = explode('.', $peer_ip, 2);
        $peer_ip  = hex2ip($peer_ip);

        if ($peer_ip  == '0.0.0.0') { $peer_ip  = ''; }
        $peer_as  = $entry['cbgpPeer2RemoteAs'];
        $peer = array('ip'            => $peer_ip,
                      'as'            => $peer_as,
                      'admin_status'  => $entry['cbgpPeer2AdminStatus']);
        if (is_bgp_peer_valid($peer))
        {
          $p_list[$peer_ip][$peer_as] = 1;
        } else {
          unset($cisco_peers[$peer_ip]); // Remove invalid entry for suppress force rediscover
        }
      }
      // And anyway get bgpPeerLocalAddr for fix Cisco issue with incorrect random data in cbgpPeer2LocalAddr
      //$cisco_fix   = snmpwalk_cache_oid($device, 'bgpPeerLocalAddr', array(), 'BGP4-MIB');
    } else {
      echo("BGP4-MIB ");
      $bgp_peers = snmpwalk_cache_multi_oid($device, 'bgpPeerRemoteAs', array(), 'BGP4-MIB');
      foreach ($bgp_oids as $bgp_oid)
      {
        $bgp_peers = snmpwalk_cache_multi_oid($device, $bgp_oid, $bgp_peers, 'BGP4-MIB');
      }

      // Collect founded peers
      foreach ($bgp_peers as $peer_ip => $entry)
      {
        $peer_as  = snmp_dewrap32bit($entry['bgpPeerRemoteAs']); // Dewrap for 32bit ASN

        if ($peer_ip  == '0.0.0.0') { $peer_ip  = ''; }
        $peer = array('ip'            => $peer_ip,
                      'as'            => $peer_as,
                      'admin_status'  => $entry['bgpPeerAdminStatus']);
        if (is_bgp_peer_valid($peer))
        {
          $p_list[$peer_ip][$peer_as] = 1;
        } else {
          unset($bgp_peers[$peer_ip]); // Remove invalid entry for suppress force rediscover
        }
      }
    }

    if ($vendor_mib)
    {
      // Vendor specific IPv4/IPv6 BGP4 MIB
      echo("$vendor_mib ");

      // Fetch BGP counters for vendor specific MIBs
      $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerRemoteAs,            array(), $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
      if (count($vendor_bgp) > 0)
      {
        if (!isset($vendor_use_index[$vendor_PeerRemoteAddr]))
        {
          $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerRemoteAddr,          $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
          //$vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerRemoteAddrType,      $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        }
        //$vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerLocalAddr,           $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerState,               $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerAdminStatus,         $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerInUpdates,           $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerOutUpdates,          $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerInTotalMessages,     $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerOutTotalMessages,    $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerFsmEstablishedTime,  $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        $vendor_bgp = snmpwalk_cache_oid($device, $vendor_PeerInUpdateElapsedTime, $vendor_bgp, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);

        // Collect founded peers and rewrite to pretty array.
        foreach ($vendor_bgp as $idx => $entry)
        {
          if (count($vendor_use_index))
          {
            parse_bgp_peer_index($entry, $idx, $vendor_mib);
          }
          $peer_ip = hex2ip($entry[$vendor_PeerRemoteAddr]);

          //$entry[$vendor_PeerLocalAddr] = hex2ip($entry[$vendor_PeerLocalAddr]);
          $entry['idx'] = $idx;
          if ($peer_ip  == '0.0.0.0') { $peer_ip  = ''; }
          $peer_as = $entry[$vendor_PeerRemoteAs];
          $peer = array('ip'            => $peer_ip,
                        'as'            => $peer_as,
                        'admin_status'  => $entry[$vendor_PeerAdminStatus]);
          if (is_bgp_peer_valid($peer))
          {
            $p_list[$peer_ip][$peer_as] = 1;
            $vendor_peers[$peer_ip][$peer_as] = $entry;
          }
        }

        // Fetch vendor specific counters
        $vendor_counters = snmpwalk_cache_oid($device, $vendor_PeerAcceptedPrefixes,            array(), $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        if ($vendor_PeerDeniedPrefixes != '')
        {
          $vendor_counters = snmpwalk_cache_oid($device, $vendor_PeerDeniedPrefixes,   $vendor_counters, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
        }
        $vendor_counters = snmpwalk_cache_oid($device, $vendor_PeerAdvertisedPrefixes, $vendor_counters, $vendor_mib, NULL, OBS_SNMP_ALL_NUMERIC_INDEX);
      } else {
        $vendor_mib = FALSE;
      }
    }
    echo(PHP_EOL);

    if ($cisco_version)
    {
      $c_prefixes = array();
      foreach ($cbgp_oids as $cbgp_oid)
      {
        $c_oid = ($cisco_version === 2) ? str_replace('cbgpPeer', 'cbgpPeer2', $cbgp_oid) : $cbgp_oid;
        $c_prefixes = snmpwalk_cache_oid($device, $c_oid, $c_prefixes, 'CISCO-BGP4-MIB');
      }
    }

  }

  if (OBS_DEBUG > 1)
  {
    print_vars($bgp_peers);
    print_vars($cisco_peers);
    print_vars($vendor_peers);
    print_vars($p_list);
  }

  $sql  = 'SELECT * FROM `bgpPeers`';
  $sql .= ' LEFT JOIN `bgpPeers-state` USING(`bgpPeer_id`)';
  $sql .= ' WHERE `device_id` = ?';

  foreach (dbFetchRows($sql, array($device['device_id'])) as $peer)
  {
    $peer_as = $peer['bgpPeerRemoteAs'];
    $peer_ip = $peer['bgpPeerRemoteAddr'];
    $remote_ip = (strstr($peer_ip, ':')) ? Net_IPv6::compress($peer_ip) : $peer_ip; // Compact IPv6. Used only for log.

    // Check if peers exist in SNMP data
    if (isset($p_list[$peer_ip][$peer_as]))
    {
      // OK
      unset($p_list[$peer_ip][$peer_as]);
    } else {
      // This peer removed from table, force rediscover peers
      $force_discovery = TRUE;
    }

    if (!strstr($peer_ip, ':') && isset($bgp_peers[$peer_ip]) && $cisco_version !== 2)
    {
      // Common IPv4 BGP4 MIB
      foreach ($bgp_oids as $bgp_oid)
      {
        $$bgp_oid = $bgp_peers[$peer_ip][$bgp_oid];
      }
    }
    else if ($cisco_version === 2)
    {
      // Cisco BGP4 V2 MIB
      $c_index = (strstr($peer_ip, ':')) ? 'ipv6.' . ip2hex($peer_ip, ':') : 'ipv4.' . $peer_ip;
      foreach ($bgp_oids as $bgp_oid)
      {
        $c_oid = str_replace(array('bgpPeer', 'Identifier'), array('cbgpPeer2', 'RemoteIdentifier'), $bgp_oid);
        /*
        if ($bgp_oid == 'bgpPeerLocalAddr')
        {
          if (isset($cisco_fix[$peer_ip]) && strlen($cisco_fix[$peer_ip][$bgp_oid]))
          {
            // Fix incorrect IPv4 local IPs
            $cisco_peers[$c_index][$c_oid] = $cisco_fix[$peer_ip][$bgp_oid];
          } else {
            $cisco_peers[$c_index][$c_oid] = hex2ip($cisco_peers[$c_index][$c_oid]);
          }
        }
        */
        $$bgp_oid = $cisco_peers[$c_index][$c_oid];
      }
    }
    else if ($vendor_mib)
    {
      foreach ($bgp_oids as $bgp_oid)
      {
        $vendor_oid = $vendor_oids[$vendor_mib][str_replace('bgp', 'vendor_', $bgp_oid)];
        $$bgp_oid   = $vendor_peers[$peer_ip][$peer_as][$vendor_oid];
      }
    }
    print_debug(PHP_EOL."Peer: $peer_ip (State = $bgpPeerState, AdminStatus = $bgpPeerAdminStatus)");

    // FIXME I left the eventlog code for now, as soon as alerts send an entry to the eventlog this can go.
    if ($bgpPeerFsmEstablishedTime)
    {
      if (!(is_array($config['alerts']['bgp']['whitelist']) && !in_array($peer['bgpPeerRemoteAs'], $config['alerts']['bgp']['whitelist'])) && ($bgpPeerFsmEstablishedTime < $peer['bgpPeerFsmEstablishedTime'] || $bgpPeerState != $peer['bgpPeerState']))
      {
        if ($peer['bgpPeerState'] == $bgpPeerState)
        {
          log_event('BGP Session flapped: ' . $remote_ip . ' (AS' . $peer['bgpPeerRemoteAs'] . '), time '. formatUptime($bgpPeerFsmEstablishedTime) . ' ago', $device, 'bgp_peer', $peer['bgpPeer_id']);
        }
        else if ($bgpPeerState == "established")
        {
          log_event('BGP Session Up: ' . $remote_ip . ' (AS' . $peer['bgpPeerRemoteAs'] . '), time '. formatUptime($bgpPeerFsmEstablishedTime) . ' ago', $device, 'bgp_peer', $peer['bgpPeer_id'], 'warning');
        }
        else if ($peer['bgpPeerState'] == "established")
        {
          log_event('BGP Session Down: ' . $remote_ip . ' (AS' . $peer['bgpPeerRemoteAs'] . '), time '. formatUptime($bgpPeerFsmEstablishedTime) . ' ago.', $device, 'bgp_peer', $peer['bgpPeer_id'], 'warning');
        }
      }
    }

    check_entity('bgp_peer', $peer, array('bgpPeerState' => $bgpPeerState, 'bgpPeerAdminStatus' => $bgpPeerAdminStatus, 'bgpPeerFsmEstablishedTime' => $bgpPeerFsmEstablishedTime));

    $polled = time();
    $polled_period = $polled - $peer['bgpPeer_polled'];

    print_debug("[ polled $polled -> period $polled_period ]");

    rrdtool_update_ng($device, 'bgp', array(
      'bgpPeerOutUpdates'  => $bgpPeerOutUpdates,
      'bgpPeerInUpdates'   => $bgpPeerInUpdates,
      'bgpPeerOutTotal'    => $bgpPeerOutTotalMessages,
      'bgpPeerInTotal'     => $bgpPeerInTotalMessages,
      'bgpPeerEstablished' => $bgpPeerFsmEstablishedTime,
      ), $peer_ip);

    $graphs['bgp_updates'] = TRUE;

    // Update states
    $peer['update'] = array();
    //foreach (array('bgpPeerState', 'bgpPeerAdminStatus', 'bgpPeerLocalAddr', 'bgpPeerIdentifier') as $oid)
    foreach (array('bgpPeerState', 'bgpPeerAdminStatus') as $oid)
    {
      if ($$oid != $peer[$oid]) { $peer['update'][$oid] = $$oid; }
    }

    if (count($peer['update']))
    {
      dbUpdate($peer['update'], 'bgpPeers', '`bgpPeer_id` = ?', array($peer['bgpPeer_id']));
    }

    // Update metrics
    $metrics = array('bgpPeerInUpdates', 'bgpPeerOutUpdates','bgpPeerInTotalMessages','bgpPeerOutTotalMessages');
    foreach ($metrics as $oid)
    {
      $peer['state'][$oid] = $$oid;
      if (isset($peer[$oid]) && $peer[$oid] != "0")
      {
        $peer['state'][$oid.'_delta'] = $peer['state'][$oid] - $peer[$oid];
        $peer['state'][$oid.'_rate']  = $oid_diff / $polled_period;
        if ($peer['state'][$oid.'_rate'] < 0)
        {
          $peer['state'][$oid.'_rate'] = '0';
          echo($oid." went backwards.");
        }

        if ($config['statsd']['enable'] == TRUE)
        {
          // Update StatsD/Carbon
          StatsD::gauge(str_replace(".", "_", $device['hostname']).'.'.'bgp'.'.'.str_replace(".", "_", $peer_ip).'.'.$oid, $$oid);
        }
      }
    }

    if (!is_numeric($peer['bgpPeer_polled']))
    {
      dbInsert(array('bgpPeer_id' => $peer['bgpPeer_id']), 'bgpPeers-state');
    }
    $peer['state']['bgpPeerFsmEstablishedTime'] = $bgpPeerFsmEstablishedTime;
    $peer['state']['bgpPeerInUpdateElapsedTime'] = $bgpPeerInUpdateElapsedTime;
    $peer['state']['bgpPeer_polled'] = $polled;
    dbUpdate($peer['state'], 'bgpPeers-state', '`bgpPeer_id` = ?', array($peer['bgpPeer_id']));

    $table_row = array();
    $table_row[] = $peer_ip;
    $table_row[] = $peer['bgpPeerRemoteAs'];
    $table_row[] = truncate($peer['astext'], 15);
    $table_row[] = $bgpPeerAdminStatus;
    $table_row[] = $bgpPeerState;
    $table_row[] = $peer['bgpPeerLocalAddr'];
    $table_row[] = formatUptime($bgpPeerFsmEstablishedTime);
    $table_row[] = formatUptime($bgpPeerInUpdateElapsedTime);
    $table_rows[] = $table_row;
    unset($table_row);

    if ($cisco_version || $vendor_mib)
    {
      // Poll each AFI/SAFI for this peer
      $peer_afis = dbFetchRows('SELECT * FROM `bgpPeers_cbgp` WHERE `device_id` = ? AND `bgpPeerRemoteAddr` = ?', array($device['device_id'], $peer_ip));
      foreach ($peer_afis as $peer_afi)
      {
        $afi = $peer_afi['afi'];
        $safi = $peer_afi['safi'];
        print_debug("$afi $safi");

        if ($cisco_version)
        {
          $c_index = ($cisco_version === 2) ? "$c_index.$afi.$safi" : "$peer_ip.$afi.$safi";
          foreach ($cbgp_oids as $cbgp_oid)
          {
            $c_oid = ($cisco_version === 2) ? str_replace('cbgpPeer', 'cbgpPeer2', $cbgp_oid) : $cbgp_oid;
            #$c_prefixes = snmpwalk_cache_oid($device, $c_oid, $c_prefixes, 'CISCO-BGP4-MIB');
            $$cbgp_oid = $c_prefixes[$c_index][$c_oid];
          }
        }

        if ($vendor_mib)
        {
          // Missing: cbgpPeerAdminLimit cbgpPeerPrefixThreshold cbgpPeerPrefixClearThreshold cbgpPeerSuppressedPrefixes cbgpPeerWithdrawnPrefixes

          // See posible AFI/SAFI here: https://www.juniper.net/techpubs/en_US/junos12.3/topics/topic-map/bgp-multiprotocol.html
          $afis['1'] = 'ipv4';
          $afis['2'] = 'ipv6';
          $afis['ipv4'] = '1';
          $afis['ipv6'] = '2';
          $safis = array('unicast'    => 1,
                         'multicast'  => 2,
                         'mpls'       => 4,
                         'mdt'        => 66,
                         'vpn'        => 128,
                         'vpn multicast' => 129);

          //$peer_index = $vendor_peers[$peer_ip][$peer_as][$vendor_PeerIndex];
          $peer_index = $peer_afi['bgpPeerIndex'];
          if (isset($vendor_counters[$peer_index.'.'.$afi.'.'.$safis[$safi]]))
          {
            $index = $peer_index . '.' . $afi        . '.' . $safis[$safi];
          } else {
            $index = $peer_index . '.' . $afis[$afi] . '.' . $safis[$safi];
          }

          $cbgpPeerAcceptedPrefixes   = $vendor_counters[$index][$vendor_PeerAcceptedPrefixes];
          $cbgpPeerDeniedPrefixes     = $vendor_counters[$index][$vendor_PeerDeniedPrefixes];
          $cbgpPeerAdvertisedPrefixes = $vendor_counters[$index][$vendor_PeerAdvertisedPrefixes];
          $cbgpPeerSuppressedPrefixes = "U";
          $cbgpPeerWithdrawnPrefixes  = "U";
        }

        // Update cbgp states
        $peer['c_update']['AcceptedPrefixes']     = $cbgpPeerAcceptedPrefixes;
        $peer['c_update']['DeniedPrefixes']       = $cbgpPeerDeniedPrefixes;
        $peer['c_update']['PrefixAdminLimit']     = $cbgpPeerPrefixAdminLimit;
        $peer['c_update']['PrefixThreshold']      = $cbgpPeerPrefixThreshold;
        $peer['c_update']['PrefixClearThreshold'] = $cbgpPeerPrefixClearThreshold;
        $peer['c_update']['AdvertisedPrefixes']   = $cbgpPeerAdvertisedPrefixes;
        $peer['c_update']['SuppressedPrefixes']   = $cbgpPeerSuppressedPrefixes;
        $peer['c_update']['WithdrawnPrefixes']    = $cbgpPeerWithdrawnPrefixes;
        if (dbFetchCell('SELECT COUNT(`cbgp_id`) FROM `bgpPeers_cbgp-state` WHERE `cbgp_id` = ?', array($peer_afi['cbgp_id'])) == 0)
        {
          dbInsert(array('cbgp_id' => $peer_afi['cbgp_id']), 'bgpPeers_cbgp-state');
        }
        dbUpdate($peer['c_update'], 'bgpPeers_cbgp-state', '`cbgp_id` = ?', array($peer_afi['cbgp_id']));

        // Update cbgp StatsD
        if ($config['statsd']['enable'] == TRUE)
        {
          foreach (array('AcceptedPrefixes', 'DeniedPrefixes', 'AdvertisedPrefixes', 'SuppressedPrefixes', 'WithdrawnPrefixes') as $oid)
          {
            // Update StatsD/Carbon
            $r_oid = 'cbgpPeer'.$oid;
            StatsD::gauge(str_replace('.', '_', $device['hostname']).'.'.'bgp' . '.' . str_replace('.', '_', $peer_ip).".$afi.$safi" . '.' . $oid, $$r_oid);
          }
        }

        // Update RRD
        rrdtool_update_ng($device, 'cbgp', array(
          'AcceptedPrefixes'   => $cbgpPeerAcceptedPrefixes,
          'DeniedPrefixes'     => $cbgpPeerDeniedPrefixes,
          'AdvertisedPrefixes' => $cbgpPeerAdvertisedPrefixes,
          'SuppressedPrefixes' => $cbgpPeerSuppressedPrefixes,
          'WithdrawnPrefixes'  => $cbgpPeerWithdrawnPrefixes,
        ), $peer_ip.".$afi.$safi");

        $graphs['bgp_prefixes_'.$afi.$safi] = TRUE;

        $c_table_row = array();
        $c_table_row[] = $peer_ip;
        $c_table_row[] = $peer['bgpPeerRemoteAs'];
        $c_table_row[] = $afi."-".$safi;
        $c_table_row[] = $cbgpPeerAcceptedPrefixes;
        $c_table_row[] = $cbgpPeerDeniedPrefixes;
        $c_table_row[] = $cbgpPeerAdvertisedPrefixes;
        $c_table_rows[] = $c_table_row;
        unset($c_table_row);

      } # while
    } # os_group=cisco | vendors

  } // End While loop on peers

  if (count($table_rows))
  {
    echo(PHP_EOL);
    $headers = array('%WPeer IP%n', '%WASN%n', '%WAS%n', '%WAdmin%n', '%WState%n', '%WLocal IP%n', '%WEstablished Time%n', '%WLast Update%n');
    print_cli_table($table_rows, $headers, "Sessions");

    $headers = array('%WPeer IP%n', '%WASN%n', '%WAFI/SAFI%n', '%WAccepted Pfx%n', '%WDenied Pfx%n', '%WAdvertised Pfx%n');
    print_cli_table($c_table_rows, $headers, "Address Families");

  }

  foreach ($p_list as $peer_ip => $entry)
  {
    // Check if new peers found
    $force_discovery = $force_discovery || !empty($entry);
  }
  if ($force_discovery)
  {
    // Force rediscover bgp peers
 	 	print_debug("BGP peers list for this device changed, force rediscover BGP.");
 	 	force_discovery($device, 'bgp-peers');
  }

} // End check for BGP support

// Clean
unset($bgp_peers, $vendor_peers, $vendor_mib, $cisco_version, $cisco_peers, $c_prefixes);

// EOF