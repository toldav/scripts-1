#!/home/rbrown/python/bin/python

import spacewalker

if __name__ == '__main__':
    sc = spacewalker.SatelliteConnect()
    #sysid = sc.get_systemid('nebula.nydc.fxcorp.prv')
    #print(sc.get_systeminfo('nebula.nydc.fxcorp.prv').get_memory())
    #print(sc.get_systeminfo('nebula.nydc.fxcorp.prv').get_hostip())
    #print(sc.get_systeminfo('nebula.nydc.fxcorp.prv').get_runningkernel())

    for host in sc.get_systemgroup("Effex").list_systems():
        print(host)

