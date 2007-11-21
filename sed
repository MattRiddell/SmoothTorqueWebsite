#!/bin/bash
for fl in *.php; do
mv $fl $fl.old
sed 's#\$link\ =\ #include\ \"admin/db_config\.php\"\;//#g'  $fl.old > $fl
rm -f $fl.old
done
