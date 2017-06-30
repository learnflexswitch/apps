2. snmpbulkwalk:
USAGE: snmpbulkwalk [OPTIONS] AGENT [OID]
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
    (default: $HOME/.snmp/mibs:/usr/share/snmp/mibs:/usr/share/snmp/mibs/iana:/usr/share/snmp/mibs/ietf:/usr/share/mibs/site:/usr/share/snmp/mibs:/usr/share/mibs/iana:/usr/share/mibs/ietf:/usr/share/mibs/netsnmp)
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
                          c:       do not check returned OIDs are increasing
                          i:       include given OIDs in the search range
                          n<NUM>:  set non-repeaters to <NUM>
                          p:       print the number of variables found
                          r<NUM>:  set max-repeaters to <NUM>
  
   
#diskIOEntry
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m UCD-DISKIO-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' diskIOEntry


#dot1qPortVlanTable
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m Q-BRIDGE-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' dot1qPortVlanTable

#dot1qTpFdbEntry
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OqsX \
-m Q-BRIDGE-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' dot1qTpFdbEntry 2>/dev/null

#dot1qVlanStaticUntaggedPorts
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m Q-BRIDGE-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' dot1qVlanStaticUntaggedPorts 2>/dev/null

#dot3StatsDuplexStatus
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m EtherLike-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' dot3StatsDuplexStatus

#hrDeviceDescr
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
 -m HOST-RESOURCES-MIB:HOST-RESOURCES-TYPES -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
 'udp':'10.0.130.113':'161' hrDeviceDescr 2>/dev/null

#hrDeviceType 
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m HOST-RESOURCES-MIB:HOST-RESOURCES-TYPES -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' hrDeviceType 2>/dev/null


#hrProcessorLoad
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m HOST-RESOURCES-MIB:HOST-RESOURCES-TYPES -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' hrProcessorLoad 2>/dev/null

#hrStorageEntry 
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m HOST-RESOURCES-MIB:HOST-RESOURCES-TYPES -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' hrStorageEntry 2>/dev/null

#icmp 
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m IP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' icmp 2>/dev/null

#ifEntry 
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m IF-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' ifEntry 2>/dev/null 

#ifXEntry
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m IF-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' ifXEntry 2>/dev/null

#ipSystemStats
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m IP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' ipSystemStats 2>/dev/null

#laLoadInt
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m UCD-SNMP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' laLoadInt 2>/dev/null

#mem
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m UCD-SNMP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' mem 2>/dev/null

#ospfGeneralGroup
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m OSPF-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' ospfGeneralGroup 2>/dev/null

#snmp
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m SNMPv2-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' snmp 2>/dev/null

#systemStats
snmpbulkwalk -v3 -l 'authNoPriv' -n "" -a 'MD5' -A 'password' -u 'authOnlyUser' -Pu -OQUs \
-m UCD-SNMP-MIB -M /opt/observium/mibs/rfc:/opt/observium/mibs/net-snmp \
'udp':'10.0.130.113':'161' systemStats 2>/dev/null
    

