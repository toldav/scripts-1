#!/home/rbrown/python/bin/python

import xmlrpclib
import os
import sys

class Systeminfo(object):
    def __init__(self, sysid):
        self.sysid = sysid
        self.client = SatelliteConnect().client
        self.key = SatelliteConnect().key
        self.servername = self.get_name()

    def get_name(self):
        servername = self.client.system.getName(self.key, self.sysid)
        return servername.get('name')

    def get_hostip(self):
        hostip = servername = self.client.system.getNetwork(self.key, self.sysid)
        return hostip.get('ip')

    def get_runningkernel(self):
        kernel = self.client.system.getRunningKernel(self.key, self.sysid)
        return kernel

    def get_memory(self):
        mem = self.client.system.getMemory(self.key, self.sysid)
        return(mem.get('ram',0),mem.get('swap',0))

    def get_systemid(self):
        systemlist = self.client.system.getId(self.key, host)
        for system in systemlist:
            return system.get('id')

    def __str__(self):
        return "{}".format(self.get_name())

class SatelliteConnect(object):
    SATELLITE_URL = "http://servername/rpc/api"
    SATELLITE_LOGIN = os.environ['USER']
    SATELLITE_PASS = os.environ.get('SATELLITE_PASS',None)

    def __init__(self):
        self.client = xmlrpclib.Server(self.SATELLITE_URL, verbose=0)
        self._check_env('SATELLITE_PASS')
        try:
            self.key = self.client.auth.login(self.SATELLITE_LOGIN, self.SATELLITE_PASS)
        except xmlrpclib.Fault as err:
            print("Auth failure {} {}".format(err.faultCode, err.faultString))
            sys.exit(-1)

    def _check_env(self, env_var):
        if not os.environ.get('SATELLITE_PASS'):
            print("{} error please set environment varible {} and re-run script".format(sys.argv[0], env_var))
            sys.exit(-1)

    def get_connection(self):
        return(SatelliteConnect())

    def get_systemid(self, host):
        systemlist = self.client.system.getId(self.key, host)
        for system in systemlist:
            return system.get('id')

    def get_systeminfo(self, host):
        return Systeminfo(self.get_systemid(host))

    def get_systemlist(self):
        systemlist = self.client.system.listSystems(self.key)
        return([ system.get('name') for system in systemlist ])

if __name__ == '__main__':
    sc = SatelliteConnect()
    sysid = sc.get_systemid('servername')
    print(sc.get_systeminfo('servername').get_memory())
    print(sc.get_systeminfo('servername').get_hostip())
    print(sc.get_systeminfo('servername').get_runningkernel())
