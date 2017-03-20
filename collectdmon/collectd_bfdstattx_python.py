#!/usr/bin/env python
import os
import signal
import string
import subprocess
import sys
import json
import datetime
try:
    from flexswitchV2 import FlexSwitch
except:
    sys.path.append('/opt/flexswitch/sdk/py/')
    from flexswitchV2 import FlexSwitch

# send the bfd stats with bits/sec
class BfdStat(object):
    def __init__(self):
	print("Start monitoring bfdstat")
	
    def get_bfdstats(self, stwitch_ip):
        swtch = FlexSwitch (stwitch_ip, 8080)  # Instantiate object to talk to flexSwitch
	bfds = swtch.getAllBfdSessionStates()
        return bfds
	
    def parse_bfdstx(self, port_object):
	stat1 = port_object["Object"]["NumTxPackets"]
        return json.dumps(stat1)
    
class BfdMon(object):
    def __init__(self):
        self.plugin_name = 'collectd-bfdstattx-python'
        self.bfdstat_path = '/usr/bin/bfdstattx'
     
    def init_callback(self):
	print("Nothing to be done here now ")
 
    def configure_callback(self, conf):
	for node in conf.children:
            val = str(node.values[0]) 
            print(" config  %s"%val)
		
    def sendToCollect(self, val_type, type_instance, value):
        val = collectd.Values()
        val.plugin = self.plugin_name
        val.type = val_type
        
        val.type_instance = type_instance
        val.values = [value, ]
        val.meta={'0': True}
        val.dispatch()
		    
    def read_callback(self):
        """
        Collectd read callback
        """
        print("Read callback called")
        portstat = BfdStat()
        ports = portstat.get_bfdstats("localhost")
	for port_object in ports:
            stat = portstat.parse_bfdstx(port_object)
	    port_name = port_object["Object"]["IpAddr"]
	    print("%s : %s"%(port_name, stat))
            self.sendToCollect('derive', port_name, stat) 


if __name__ == '__main__':
     portstat = BfdStat()
     portmon = BfdMon()
     ports = portstat.get_bfdstats("localhost")
     for port_object in ports:
         stat = portstat.parse_bfdstx(port_object)
	 port_name = json.dumps(port_object["Object"]["IpAddr"])
	 print("%s : %s"%(port_name, stat))
         portmon.sendToCollect('derive', port_name, stat)


     sys.exit(0)
else:
    import collectd

    portmon = BfdMon()

    # Register callbacks
  
    collectd.register_init(portmon.init_callback) 
    collectd.register_config(portmon.configure_callback)
    collectd.register_read(portmon.read_callback)
