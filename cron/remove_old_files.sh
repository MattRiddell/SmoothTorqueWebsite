#!/bin/bash
find /var/spool/asterisk/monitor/ -mtime +30 -exec rm {} \;
