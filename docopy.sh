#/bin/bash 
/usr/bin/scp -P 99 /tmp/uploads/$1 root@dndn.venturevoip.com:/var/lib/asterisk/sounds/test.sln 2>&1
/bin/rm -f /tmp/uploads/$1
