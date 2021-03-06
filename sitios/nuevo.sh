#!/bin/sh
# Preparar nuevo sitio
# Dominio p�blico. vtamara@pasosdeJesus.org 2009

ns=$1;
if (test "$ns" == "") then {
	echo "Primer par�metro debe ser nombre de nuevo sitio";
	exit 1;
} fi;

if (test ! -f "nuevo.sh") then {
	echo "Ejecutar desde directorio SIVeL";
	exit 1;
} fi;

dt=`pwd`;
dts=`echo $dt | sed -e "s/sitios$//g"`;
dtchroot=`echo $dts | sed -e "s/^\/var\/www//g"`;
if (test "$dt" = "$dts") then {
	echo "Ejecutar desde directorio SIVeL";
	exit 1;
} fi;

edts=`echo $dts | sed -e "s/\//\\\\\\\\\//g"`
edtchroot=`echo $dtchroot | sed -e "s/\//\\\\\\\\\//g"`

#echo "edts=$edts";
#echo "edtchroot=$edtchroot";

nomplant="conf.php.plantilla"

if (test "$CON_TODO" = "1") then {
	nomplant="conf-todomodulo.php.plantilla"
} fi;

if (test "$usivel" = "") then {
	usivel=`whoami`;
} fi;
CLSIVELPG="xyz"
if (test -f /home/$usivel/.pgpass) then {
	CLSIVELPG=`grep ":sivel:" /home/$usivel/.pgpass | sed -e 's/.*:sivel://g' 2> /dev/null`
} fi;

mkdir -p $ns/DataObjects
sed -e "s/dbnombre *= *\".*\"/dbnombre = \"$ns\"/g;s/dbclave *= *\".*\"/dbclave = \"$CLSIVELPG\"/g;s/dirsitio *= *\".*\"/dirsitio = \"sitios\/$ns\"/g;s/dirserv *= *\".*\"/dirserv = \"$edtchroot\"/g" pordefecto/$nomplant > $ns/conf.php
sed -e "s/dirap *= *.*/dirap=$edts\/sitios\/$ns/g" pordefecto/vardb.sh.plantilla > $ns/vardb.sh
touch $ns/ultimoenvio.txt
sudo chown -f www:www $ns/ultimoenvio.txt
sudo chgrp www $ns/conf*.php
sudo chmod o-rwx $ns/conf*php
touch $ns/DataObjects/estructura-dataobject.ini
touch $ns/DataObjects/estructura-dataobject.links.ini
touch $ns/DataObjects/$ns.ini
touch $ns/DataObjects/$ns.links.ini

sudo chown www:www $ns/DataObjects/$ns.ini
sudo chown www:www $ns/DataObjects/$ns.links.ini
cp ../imagen/sivel11.jpg $ns/centro.jpg
cp ../centro_principal.html $ns/centro.jpg
if (test -f ../imagen/fondo.jpg) then {
    cp ../imagen/fondo.jpg $ns/fondo.jpg
} fi;

cd $ns
if (test "$SIN_CREAR" != "1") then {

	../../bin/creapg.sh
	../../bin/agus.sh
	sudo ../../bin/creaesquema.sh
} fi;
sudo chown -f www:www DataObjects/$ns.ini
sudo chown -f www:www DataObjects/$ns.links.ini
