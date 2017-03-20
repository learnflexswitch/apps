import sys
from flexswitch import FlexSwitch

########## Command auto-completion dictionaries/tree##############


#######################SHOW COMMANDS##########################
_SHOW_BGP_NEIGH={'neighbors':['detail']}
_SHOW_BGP={'bgp':[_SHOW_BGP_NEIGH,'summary']}
_SHOW_OSPF_NEIGH={'neighbors':['detail']}
_SHOW_OSPF={'ospf':[_SHOW_BGP_NEIGH,'interface']}
_SHOW_ROUTE={'route':['summary', 'interface', 'bgp', 'ospf', 'static']
			}
_SHOW_PCHANNEL={'summary':['interface'], 'load-balance':[''], 'interface':['detail']
}

#BASE for all show commands			

_SHOW_BASE = {'ip':[_SHOW_BGP,_SHOW_ROUTE,_SHOW_OSPF,'interface','arp'],
			'version':[''], 'inventory':['detail'], 'interface':['counters'],
			'port-channel':_SHOW_PCHANNEL,'bfd':['interface','summary']
			}


#######################CONFIGURE COMMANDS##########################

class Commands():

	def __init__(self,switch_ip):
   		"""init class """
   		self.fs_info = FlexSwitch_info(switch_ip)

   	def parser(self, dict, line):
   		match=[]
   		info={}
		for key, val in dict.items():
			if  key.startswith(line):
				info = {key:val}
				match.append(info)
		if len(match):
			if len(match) > 1:
				sys.stdout.write('% Ambiguous Command...Choose from the following:\n\n') 
				for i in match:
					for command, descrip in i.items():
						#print command, descrip
						sys.stdout.write('   %s\t%s\n' % ( command,descrip) )
				return str(False),match
			else:
				for i in match:
					for key, val in i.items():
						command=key
				return str(True),command
		else:
			sys.stdout.write('   % Invalid Command\n') 
			return str(False),match
			
	def show_commands(self, arg):
		" Show running system information "
		args=arg.split()
		#print args[0], args[1]
		if len(args):
			if [value for key, value in _SHOW_BASE.items() if args[0] in key.lower()]:
				print args[0],"=",key
			try:
				if 'ip' in args[0]:
					if 'bgp' in args[1]:
						#print bgp table
						if 'neighbors' in args[2]:
							#print BGP neighbors
							sys.stdout.write("neighbor\n")
						elif 'summary' in args[2]:
							self.fs_info.displayBGPPeers()
						else:
							sys.stdout.write("% Invalid command \n")	
					elif 'route' in args[1]:
						self.fs_info.displayRoutes()
					elif 'arp' in args[1]:
						self.fs_info.displayARPEntries()
					elif 'ospf' in args[1]:
						if 'interface' in args[2]:
							self.fs_info.verifyDRElectionResult()
						else:
							sys.stdout.write("% Invalid command \n")
					else:
						sys.stdout.write("% Invalid command \n")
				elif 'interface' in args[0]:
					if 'counters' in args[1]:
						if len(args) == 2:
							self.fs_info.displayPortObjects()
						else:
							if 'cpu' in args[2]:
								self.fs_info.displayCPUPortObjects()
							else:
								sys.stdout.write('% Invalid command\n')  
					else:
						sys.stdout.write("% Invalid command \n")	
				elif 'vlan' in args[0]:
					if len(args) >=2:
						if 1 <= int(args[1]) <= 4094:
							self.fs_info.getVlanInfo(args[1])
						else:
							sys.stdout.write("% Invalid command \n")
					else:
						sys.stdout.write('% Incomplete command\n')
				elif 'port-channel' in args[0]:
					if 'summary' in args[1]:
						self.fs_info.getLagGroups()
					elif 'interface' in args[1]:       				
						if len(args) == 2:
							self.fs_info.getLagMembers()
						else:
							if 'detail' in args[2]:
								self.fs_info.getLagMembers_detail()
							else:
								sys.stdout.write('% Invalid Command\n')     				
					else:
						sys.stdout.write('% Invalid Command\n')
				else:
					sys.stdout.write('% Incomplete Command\n')
			except IndexError:
				sys.stdout.write('% Incomplete Command\n')
				
			except :
				sys.stdout.write('% Loss connectivity to %s \n' % switch_name )
		else:
			sys.stdout.write('% Incomplete Command\n')

	def auto_show(self, text, line, begidx, endidx):
		lines=line.strip()
		list=[]
		if 'show' in lines:
			if 'ip' in lines:
				if 'bgp' in lines:
					if 'neighbors' in lines:
						for keys in _SHOW_BGP.get('bgp'):
							if type(keys) is dict:
								for var in keys:
									list.append(var)
						return [i for i in list if i.startswith(text)]
						
					elif 'summary' in lines:
						return
					else:
						for keys in _SHOW_BGP.get('bgp'):
							if type(keys) is dict:
								for var in keys:
									list.append(var)
							else:
								list.append(keys)
						return [i for i in list if i.startswith(text)]
				elif 'route' in lines:
					if 'summary' in lines:
						for keys in _SHOW_ROUTE.get('route'):
							if keys in lines:
								continue
							else:
								list.append(keys)
						return [i for i in list if i.startswith(text)]
					
					else:
						for keys in _SHOW_ROUTE.get('route'):
							if keys in lines:
								continue
							else:
								list.append(keys)
						return [i for i in list if i.startswith(text)]
				else:
					for keys in _SHOW_BASE.get('ip'):
						if type(keys) is dict:
							for var in keys:
								list.append(var)    				
						else:
							if keys in lines:
								continue
							else:
								list.append(keys)			
					return [i for i in list if i.startswith(text)]
					 
			elif 'port-channel' in lines:
				#print "goo"
				if 'summary' in lines:
					for keys in _SHOW_PCHANNEL.get('summary'):
						if keys in lines:
							continue
						else:
							list.append(keys)
					return [i for i in list if i.startswith(text)]
				elif 'load-balance' in lines:
					for keys in _SHOW_PCHANNEL.get('load-balance'):
						if keys in lines:
							continue
						else:
							list.append(keys)
					return [i for i in list if i.startswith(text)]	
				elif 'interface' in lines:
					for keys in _SHOW_PCHANNEL.get('interface'):
						if keys in lines:
							continue
						else:
							list.append(keys)
					return [i for i in list if i.startswith(text)]	 
					
				else:
					for keys in _SHOW_PCHANNEL:
						if keys in lines:
							continue
						else:
							if keys in lines:
								continue
							else:
								list.append(keys)
					return [i for i in list if i.startswith(text)]   		    			    		
			elif 'interface' in lines:	
				return [i for i in _SHOW_BASE.get('interface') if i.startswith(text)]
			else:
				return [i for i in _SHOW_BASE if i.startswith(text)]


	def global_commands(self, arg):
		""" Global Configuration Commands """
	
	def interface_commands(self, arg):
		"""Interface configuration Commands"""
		
   		
class FlexSwitch_info():

   def __init__(self,switch_ip):
   		self.switch_ip=switch_ip
   		self.swtch = FlexSwitch(self.switch_ip,8080)
   		
   def displayBGPPeers(self):	   
		   sessionState=  {  0: "Idle",
							 1: "Connect",
							 2: "Active",
							 3: "OpenSent",
							 4: "OpenConfirm",
							 5: "Established"
						   } 
	
		   peers = self.swtch.getObjects('BGPNeighborStates')
		   if len(peers)>=0: 
			   print '\n'
			   print 'Neighbor   LocalAS   PeerAS     State      RxNotifications    RxUpdates   TxNotifications TxUpdates'
		   for pr in peers:
			   print '%s    %s      %s     %s       %s              %s              %s           %s' %(pr['NeighborAddress'],
																				  pr['LocalAS'],
																				  pr['PeerAS'],
																				  sessionState[int(pr['SessionState'] -1)],
																				  pr['Messages']['Received']['Notification'],
																				  pr['Messages']['Received']['Update'],
																				  pr['Messages']['Sent']['Notification'],
																				  pr['Messages']['Sent']['Update'])

		   print "\n"
			   
   def displayRoutes(self):
     	routes = self.swtch.getObjects('IPV4Routes')
     	if len(routes)>=0:
     	    print '\n'
     	    print 'Network            Mask         NextHop         Cost       Protocol   IfType IfIndex'
     	for rt in routes:
     	    print '%s %s %s %4d   %9s    %5s   %4s' %(rt['DestinationNw'].ljust(15), 
     	                                                    rt['NetworkMask'].ljust(15),
     	                                                    rt['NextHopIp'].ljust(15), 
     	                                                    rt['Cost'], 
     	                                                    rt['Protocol'], 
     	                                                    rt['OutgoingIntfType'], 
     	                                                    rt['OutgoingInterface'])
        print "\n"

   def displayARPEntries(self):
        arps = self.swtch.getObjects('ArpEntrys')
        if len(arps)>=0:
            print '\n'
            print 'IP Address	MacAddress   	    TimeRemaining  	Vlan 	  Intf'
        for d in arps:
            print  '%s	%s    %s	 %s	%s' %(d['IpAddr'],
						d['MacAddr'],
						d['ExpiryTimeLeft'],
						d['Vlan'],
						d['Intf'])
        print "\n"

   def displayPortObjects(self):
        ports = self.swtch.getObjects('PortStates')
        if len(ports):
            print '\n'
            print 'Port         InOctets   InUcastPkts   InDiscards  InErrors     InUnknownProtos   OutOctets OutUcastPkts   OutDiscards   OutErrors'
        for d in ports:
            if d['IfIndex'] == 0:
        		continue
            #if sum(d['PortStats']):
            print '%s  %8d %10d   %10d    %8d   %15d   %9d   %12d   %11d   %11d' %("fpPort-"+str(d['IfIndex']),
                                                                d['PortStats'][0],
                                                                d['PortStats'][1],
                                                                d['PortStats'][2],
                                                                d['PortStats'][3],
                                                                d['PortStats'][4],
                                                                d['PortStats'][5],
                                                                d['PortStats'][6],
                                                                d['PortStats'][7],
                                                                d['PortStats'][8])
        print "\n"
   def displayCPUPortObjects(self):
        ports = self.swtch.getObjects('PortStates')
        if len(ports):
            print '\n'
            print 'Port         InOctets   InUcastPkts   InDiscards  InErrors     InUnknownProtos   OutOctets OutUcastPkts   OutDiscards   OutErrors'
        for d in ports:
            if d['IfIndex'] == 0:
            	print '%s  %8d %10d   %10d    %8d   %15d   %9d   %12d   %11d   %11d' %("fpPort-"+str(d['IfIndex']),
                                                                d['PortStats'][0],
                                                                d['PortStats'][1],
                                                                d['PortStats'][2],
                                                                d['PortStats'][3],
                                                                d['PortStats'][4],
                                                                d['PortStats'][5],
                                                                d['PortStats'][6],
                                                                d['PortStats'][7],
                                                                d['PortStats'][8])
        print "\n"

   def verifyDRElectionResult(self):
        ospfIntfs = self.swtch.getObjects('OspfIfEntryStates')
        print '\n'
        #self.assertNotEqual(len(ospfIntfs) != 0)
        if len(ospfIntfs)>=0:
            print 'IfAddr IfIndex  State  DR-RouterId DR-IpAddr BDR-RouterI BDR-IpAddr NumEvents LSACount LSACksum'

        for d in ospfIntfs:
            if sum(d['ospfIntfs']):
                print '%3s  %3d %10s   %10s    %8s   %15s   %9s   %12s   %11s   %11s' %( d['IfIpAddressKey'],
                                                                                        d['AddressLessIfKey'],
                                                                                        d['IfStat'],
                                                                                        d['IfDesignatedRoute'],
                                                                                        d['IfBackupDesignatedRoute'],
                                                                                        d['IfEvent'],
                                                                                        d['IfLsaCoun'],
                                                                                        d['IfLsaCksumSu'],
                                                                                        d['IfDesignatedRouterI'],
                                                                                        d['IfBackupDesignatedRouterI'])
        print "\n"            
   def getVlanInfo (self, vlanId):
        for vlan in self.swtch.getObjects ('VlanStates'):
            print vlan 
            if vlan['VlanId'] == vlanId:
                print int(vlan['IfIndex'])

   def getLagMembers(self):
      members = self.swtch.getObjects('AggregationLacpMemberStateCounterss')
      print '\n---- LACP Members----'
      if len(members):
   	   for d in members:
   		   print '+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n'
   		   print 'name: ' + d['NameKey'] + ' interface: ' + d['Interface']
   		   print 'enabled: %s' % d['Enabled']
   		   print 'lagtype: ' + ('LACP' if not d['LagType'] else 'STATIC')
   		   print 'operkey: %s' % d['OperKey']
   		   print 'mode: ' + ('ACTIVE' if not d['LacpMode'] else 'PASSIVE')
   		   print 'interval: %s' % (('SLOW' if d['Interval'] else 'FAST'))
   		   print 'system:\n'
   		   print '\tsystemmac: %s' % d['SystemIdMac']
   		   print '\tsysteprio: %s' % d['SystemPriority']
   		   print '\tsystemId: %s' % d['SystemId']
   		   print 'actor:'
   		   stateStr = '\tstate: '
   		   for s in ('Activity', 'Timeout', 'Aggregatable', 'Synchronization', 'Collecting', 'Distributing'):
   			   if s == 'Synchronization' and not d[s]:
   				   stateStr += s + ', '
   			   elif s == 'Activity' and not d[s]:
   				   stateStr += s + ', '
   			   elif s in ('Activity', 'Synchronization'):
   				   continue
   			   elif d[s]:
   				   stateStr += s + ', '
   		   print stateStr.rstrip(',')
   
   		   print '\tstats:'
   		   for s in ('LacpInPkts', 'LacpOutPkts', 'LacpRxErrors', 'LacpTxErrors', 'LacpUnknownErrors', 'LacpErrors', 'LampInPdu', 'LampOutPdu', 'LampInResponsePdu', 'LampOutResponsePdu'):
   			   print '\t' + s, ': ', d[s]
   
   		   print 'partner:\n'
   		   print '\t' + 'key: %s' % d['PartnerKey']
   		   print '\t' + 'partnerid: ' + d['PartnerId']
   


   def getLagMembers_detail(self): 
      RxMachineStateDict = {
          0 : "RX_CURRENT",
          1 : "RX_EXPIRED",
          2 : "RX_DEFAULTED",
          3 : "RX_INITIALIZE",
          4 : "RX_LACP_DISABLED",
      	5 : "RX_PORT_DISABLE",
      }
      
      MuxMachineStateDict = {
          0 : "MUX_DETACHED",
          1 : "MUX_WAITING",
          2 : "MUX_ATTACHED",
          3 : "MUX_COLLECTING",
      	4 : "MUX_DISTRIBUTING",
          5 : "MUX_COLLECTING_DISTRIBUTING",
      }
      
      ChurnMachineStateDict = {
          0 : "CDM_NO_CHURN",
          1 : "CDM_CHURN",
      }
      members = self.swtch.getObjects('AggregationLacpMemberStateCounterss')
      print '\n---- LACP Members----'
      if len(members):
   	   for d in members:
   		   print '+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n'
   		   print 'name: ' + d['NameKey'] + ' interface: ' + d['Interface']
   		   print 'enabled: %s' % d['Enabled']
   		   print 'lagtype: ' + ('LACP' if not d['LagType'] else 'STATIC')
   		   print 'operkey: %s' % d['OperKey']
   		   print 'mode: ' + ('ACTIVE' if not d['LacpMode'] else 'PASSIVE')
   		   print 'interval: %s' % (('SLOW' if d['Interval'] else 'FAST'))
   		   print 'system:\n'
   		   print '\tsystemmac: %s' % d['SystemIdMac']
   		   print '\tsysteprio: %s' % d['SystemPriority']
   		   print '\tsystemId: %s' % d['SystemId']
   		   print 'actor:'
   		   stateStr = '\tstate: '
   		   for s in ('Activity', 'Timeout', 'Aggregatable', 'Synchronization', 'Collecting', 'Distributing'):
   			   if s == 'Synchronization' and not d[s]:
   				   stateStr += s + ', '
   			   elif s == 'Activity' and not d[s]:
   				   stateStr += s + ', '
   			   elif s in ('Activity', 'Synchronization'):
   				   continue
   			   elif d[s]:
   				   stateStr += s + ', '
   		   print stateStr.rstrip(',')
   
   		   print '\tstats:'
   		   for s in ('LacpInPkts', 'LacpOutPkts', 'LacpRxErrors', 'LacpTxErrors', 'LacpUnknownErrors', 'LacpErrors', 'LampInPdu', 'LampOutPdu', 'LampInResponsePdu', 'LampOutResponsePdu'):
   			   print '\t' + s, ': ', d[s]
   
   		   print 'partner:\n'
   		   print '\t' + 'key: %s' % d['PartnerKey']
   		   print '\t' + 'partnerid: ' + d['PartnerId']
   		   print 'debug:\n'
   		   try:
   			   print '\t' + 'debugId: %s' % d['DebugId']
   			   print '\t' + 'RxMachineState: %s' % RxMachineStateDict[d['RxMachine']]
   			   print '\t' + 'RxTime (rx pkt rcv): %s' % d['RxTime']
   			   print '\t' + 'MuxMachineState: %s' % MuxMachineStateDict[d['MuxMachine']]
   			   print '\t' + 'MuxReason: %s' % d['MuxReason']
   			   print '\t' + 'Actor Churn State: %s' % ChurnMachineStateDict[d['ActorChurnMachine']]
   			   print '\t' + 'Partner Churn State: %s' % ChurnMachineStateDict[d['PartnerChurnMachine']]
   			   print '\t' + 'Actor Churn Count: %s' % d['ActorChurnCount']
   			   print '\t' + 'Partner Churn Count: %s' % d['PartnerChurnCount']
   			   print '\t' + 'Actor Sync Transition Count: %s' % d['ActorSyncTransitionCount']
   			   print '\t' + 'Partner Sync Transition Count: %s' % d['PartnerSyncTransitionCount']
   			   print '\t' + 'Actor LAG ID change Count: %s' % d['ActorChangeCount']
   			   print '\t' + 'Partner LAG ID change Count: %s' % d['PartnerChangeCount']
   		   except Exception as e:
   			   print e      
   
   def getLagGroups(self):
      lagGroup = self.swtch.getObjects('AggregationLacpStates')
      print '\n'
      if len(lagGroup)>=0:
   	   print 'Name      Ifindex      LagType   Description      Enabled   MinLinks   Interval   Mode          SystemIdMac            SystemPriority    HASH'
   
   	   for d in lagGroup:
   		   print '%7s  %7s    %7s  %15s    %8s   %2s     %8s      %6s   %20s         %s              %s' %(d['NameKey'],
   														   d['Ifindex'],
   														   "LACP" if int(d['LagType']) == 0 else "STATIC",
   														   d['Description'],
   														   "Enabled" if bool(d['Enabled']) else "Disabled",
   														   d['MinLinks'],
   														   "FAST" if int(d['Interval']) == 0 else "SLOW",
   														   "ACTIVE" if int(d['LacpMode']) == 0 else "PASSIVE",
   														   d['SystemIdMac'],
   														   d['SystemPriority'],
   														   d['LagHash'])
   		  		