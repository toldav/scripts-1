#!/home/rbrown/python/bin/python
# This script will dump all systems in spacewalk and display the system kernel version.

import xmlrpclib
import os
import sys

class Systeminfo(object):
    def __init__(self, sysid):
        self.sysid = sysid
        self.client = SatelliteConnect().client
        self.key = SatelliteConnect().key

    def get_runningkernel(self):
        kernel = self.client.system.getRunningKernel(self.key, self.sysid)

        if kernel:
            return kernel
        else:
            return None

class SatelliteConnect(object):
    SATELLITE_URL = "http://satellite/rpc/api"
    SATELLITE_LOGIN = os.environ['USER']
    SATELLITE_PASS = os.environ.get('SATELLITE_PASS',None)

    def __init__(self):
        self.client = xmlrpclib.Server(self.SATELLITE_URL, verbose=0)
        self._check_env('SATELLITE_PASS')
        self.key = self.client.auth.login(self.SATELLITE_LOGIN, self.SATELLITE_PASS)

    def _check_env(self, env_var):
        if not os.environ.get(env_var):
            print("{} error please set environment varible {} and re-run script".format(sys.argv[0], env_var))
            sys.exit(-1)

    def get_connection(self):
        return(SatelliteConnect())

    def get_systemid(self, host):
        systemlist = self.client.system.getId(self.key, host)
        for system in systemlist:
            return Systeminfo(system.get('id'))

    def get_systemlist(self):
        systemlist = self.client.system.listSystems(self.key)
        return([ system.get('name') for system in systemlist ])

if __name__ == '__main__':
    sc = SatelliteConnect()
    for system in sc.get_systemlist():
        print("{} {}".format(system, sc.get_systemid(system).get_runningkernel()))
