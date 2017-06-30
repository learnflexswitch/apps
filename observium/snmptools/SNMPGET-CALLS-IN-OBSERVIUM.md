1. snmpget definition:

snmpget [OPTIONS] AGENT OID [OID]...

OPTIONS:
  -h, --help            display this help message
  -H                    display configuration file directives understood
  -v 1|2c|3             specifies SNMP version to use
  -V, --version         display package version number
SNMP Version 1 or 2c specific
  -c COMMUNITY          set the community string
SNMP Version 3 specific
  -a PROTOCOL           set authentication protocol (MD5|SHA)
  -A PASSPHRASE         set authentication protocol pass phrase
  -e ENGINE-ID          set security engine ID (e.g. 800000020109840301)
  -E ENGINE-ID          set context engine ID (e.g. 800000020109840301)
  -l LEVEL              set security level (noAuthNoPriv|authNoPriv|authPriv)
  -n CONTEXT            set context name (e.g. bridge1)
  -u USER-NAME          set security name (e.g. bert)
  -x PROTOCOL           set privacy protocol (DES|AES)
  -X PASSPHRASE         set privacy protocol pass phrase
  -Z BOOTS,TIME         set destination engine boots/time
General communication options
  -r RETRIES            set the number of retries
  -t TIMEOUT            set the request timeout (in seconds)
Debugging
  -d                    dump input/output packets in hexadecimal
  -D[TOKEN[,...]]       turn on debugging output for the specified TOKENs
                           (ALL gives extremely verbose debugging output)
General options
  -m MIB[:...]          load given list of MIBs (ALL loads everything)
  -M DIR[:...]          look in given list of directories for MIBs
    (default: $HOME/.snmp/mibs:/usr/share/snmp/mibs:/usr/share/snmp/mibs/iana:
              /usr/share/snmp/mibs/ietf:/usr/share/mibs/site:/usr/share/snmp/mibs:
              /usr/share/mibs/iana:/usr/share/mibs/ietf:/usr/share/mibs/netsnmp)
  -P MIBOPTS            Toggle various defaults controlling MIB parsing:
                          u:  allow the use of underlines in MIB symbols
                          c:  disallow the use of "--" to terminate comments
                          d:  save the DESCRIPTIONs of the MIB objects
                          e:  disable errors when MIB symbols conflict
                          w:  enable warnings when MIB symbols conflict
                          W:  enable detailed warnings when MIB symbols conflict
                          R:  replace MIB symbols from latest module
  -O OUTOPTS            Toggle various defaults controlling output display:
                          0:  print leading 0 for single-digit hex characters
                          a:  print all strings in ascii format
                          b:  do not break OID indexes down
                          e:  print enums numerically
                          E:  escape quotes in string indices
                          f:  print full OIDs on output
                          n:  print OIDs numerically
                          q:  quick print for easier parsing
                          Q:  quick print with equal-signs
                          s:  print only last symbolic element of OID
                          S:  print MIB module-id plus last element
                          t:  print timeticks unparsed as numeric integers
                          T:  print human-readable text along with hex strings
                          u:  print OIDs using UCD-style prefix suppression
                          U:  don't print units
                          v:  print values only (not OID = value)
                          x:  print all strings in hex format
                          X:  extended index format
  -I INOPTS             Toggle various defaults controlling input parsing:
                          b:  do best/regex matching to find a MIB node
                          h:  don't apply DISPLAY-HINTs
                          r:  do not check values for range/type legality
                          R:  do random access to OID labels
                          u:  top-level OIDs must have '.' prefix (UCD-style)
                          s SUFFIX:  Append all textual OIDs with SUFFIX before parsing
                          S PREFIX:  Prepend all textual OIDs with PREFIX before parsing
  -L LOGOPTS            Toggle various defaults controlling logging:
                          e:           log to standard error
                          o:           log to standard output
                          n:           don't log at all
                          f file:      log to the specified file
                          s facility:  log to syslog (via the specified facility)

                          (variants)
                          [EON] pri:   log to standard error, output or /dev/null for level 'pri' and above
                          [EON] p1-p2: log to standard error, output or /dev/null for levels 'p1' to 'p2'
                          [FS] pri token:    log to file/syslog for level 'pri' and above
                          [FS] p1-p2 token:  log to file/syslog for levels 'p1' to 'p2'
  -C APPOPTS            Set various application specific behaviours:
                          f:  do not fix errors and retry the request

1.2. snmpget sample:

1.2.1. sysObjectID sysUpTime:
snmpget -v1 -c 'public' -Pu -OQUst -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' sysObjectID.0 sysUpTime.0 2>/dev/null
snmpget -v2c -c 'public' -Pu -OQUst -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' sysObjectID.0 sysUpTime.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUst -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' sysObjectID.0 sysUpTime.0 2>/dev/null 

1.2.2. sysDescr 
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Ovq -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' sysDescr.0 2>/dev/null

1.2.3. snmpEngineID
snmpget -v2c -c 'public' -Pu -Ovqn \
   -m SNMP-FRAMEWORK-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
    snmpEngineID.0 2>/dev/null
 
1.2.4.sysUpTime.0 sysLocation.0 sysContact.0 sysName.0 sysDescr.0 sysObjectID.0
snmpget -v1 -c 'public' -Pu -OQUs \
  -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
   sysUpTime.0 sysLocation.0 sysContact.0 sysName.0 sysDescr.0 sysObjectID.0  2>/dev/null
snmpget -v2c -c 'public' -Pu -OQUs \
  -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  sysUpTime.0 sysLocation.0 sysContact.0 sysName.0 sysDescr.0 sysObjectID.0  2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
   -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
   sysUpTime.0 sysLocation.0 sysContact.0 sysName.0 sysDescr.0 sysObjectID.0  2>/dev/null

snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQs \
  -m HOST-RESOURCES-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' hrSystemProcesses.0 hrSystemNumUsers.0 2>/dev/null   
   
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m UDP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  udpInErrors.0 udpNoPorts.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m TCP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' tcpActiveOpens.0 tcpPassiveOpens.0 tcpAttemptFails.0 tcpEstabResets.0 tcpCurrEstab.0 tcpRetransSegs.0 tcpInErrs.0 tcpOutRsts.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m UDP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  udpInDatagrams.0 udpOutDatagrams.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m TCP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  tcpInSegs.0 tcpOutSegs.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m IP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  ipForwDatagrams.0 ipInDelivers.0 ipInDiscards.0 ipOutDiscards.0 ipOutNoRoutes.0 ipReasmReqds.0 ipReasmOKs.0 ipReasmFails.0 ipFragOKs.0 ipFragFails.0 ipFragCreates.0 \
  ipInUnknownProtos.0 ipInHdrErrors.0 ipInAddrErrors.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m IP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  ipInReceives.0 ipOutRequests.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m UCD-SNMP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  memTotalReal.0 memAvailReal.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196615 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196614 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196613 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196612 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196611 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196610 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196609 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUQnv -M /opt/observium/mibs 'udp':'10.0.130.113':'161' .1.3.6.1.2.1.25.3.3.1.2.196608 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUqnv -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' .1.3.6.1.4.1.2021.13.16.2.1.3.2 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OUqnv -m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' .1.3.6.1.4.1.2021.13.16.2.1.3.1 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Oqv -m UCD-SNMP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' .1.3.6.1.4.1.2021.7890.1.101.1 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Oqv -m UCD-SNMP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' .1.3.6.1.4.1.2021.7890.1.3.1.1.6.100.105.115.116.114.111 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Oqv -m CPQSINFO-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/hp \
  'udp':'10.0.130.113':'161' cpqSiProductName.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Oqv -m MIB-Dell-10892 -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp:/opt/observium/mibs/dell \
  'udp':'10.0.130.113':'161' chassisModelName.1 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Oqv -m HOST-RESOURCES-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' hrSystemUptime.0 2>/dev/null
snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -Ovqn -m SNMP-FRAMEWORK-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
  'udp':'10.0.130.113':'161' snmpEngineID.0 2>/dev/null 

snmpget -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs -m UDP-MIB \
  -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp 'udp':'10.0.130.113':'161' \
  udpInDatagrams.0 udpOutDatagrams.0 2>/dev/null 



