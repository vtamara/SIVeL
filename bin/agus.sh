#!/bin/sh
# Permite agregar un usuario con un rol
# Dominio p�blico. vtamara@pasosdeJesus.org

DIRSEG=$1
if (test "$DIRSEG" = "") then {
	DIRSEG="."; # Suponemos que corre desde dir de un sitio
} fi;

if (test ! -f "$DIRSEG/vardb.sh" ) then {
	echo "Ejecute desde directorio de un sitio o especifique este como primer par�metro";
	exit 1;
} fi;


. $DIRSEG/vardb.sh

echo -n "usuario (sin espacios): ";
read id;
echo -n "nombre: ";
read nombre;
echo -n "descripcion: ";
read descripcion;

../../bin/psql.sh -c "SELECT * from rol;" 
echo -n "id_rol: ";
read idrol;
echo -n "anotaci�n: ";
read anotacion;
echo -n "clave: ";
stty -echo; read clave; stty echo

clavesha1=`php -n -r "echo sha1(\"$clave\");"`;
q="SET client_encoding to 'LATIN1'; INSERT INTO usuario(id_usuario, password, nombre, descripcion, id_rol)  VALUES ('$id', '$clavesha1', '$nombre', '$descripcion', '$idrol'); INSERT INTO funcionario(anotacion, nombre) VALUES ('$anotacion', '$id');" 
echo $q;
../../bin/psql.sh -c "$q"


