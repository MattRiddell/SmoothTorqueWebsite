#!/bin/bash
find /var/spool/asterisk/monitor/ -mtime +300 -exec rm {} \;
