<?php
	/*
	Remote Wake/Sleep-On-LAN Server [ARCHIVO DE CONFIGURACION]
	https://github.com/sciguy14/Remote-Wake-Sleep-On-LAN-Server
	Author: Jeremy E. Blum (http://www.jeremyblum.com)
	Actualizado y traducido por Angel Ros para ITAdicts (https://itadicts.com)
	License: GPL v3 (http://www.gnu.org/licenses/gpl.html)
	
	*/

	function registradoadmin(){
		if (isset($_SESSION["adminvalido"])==false){
			header("Refresh: 0; URL=index.php");
			ob_end_flush();
			exit();
		}
	}
	
	//Contraseña en sha256 para poder acceder.
	//Puedes usar un generador online como por ejemplo: http://www.xorbin.com/tools/sha256-hash-calculator.
	//Si no estás usando HTTPS tu contraseña puede ser interceptada con un MITM
	//Por defecto es: Hola
	$APPROVED_HASH = "e633f4fc79badea1dc5db970cf397c8248bac47cc3acf9915ba60b5d76b0e88f";
	
	//Numero de intentos de enviar WOL al equipo a despertar.
	$MAX_PINGS = 10;
	//Numero de segundos entre PINGs para comprobar si un equipo está apagado.
	$SLEEP_TIME = 5;

	//Nombre de los equipos
	$COMPUTER_NAME = array("Fijo","Portatil");
	
	//MACs de los equipos que vamos a querer despertar.
	$COMPUTER_MAC = array("11:22:33:44:55:66","00:00:00:00:00:00:00");
	
	//IP de los equipos. Se debe usar una reserva en el DHCP o IP manual en los equipos para que siempre tengan la misma.
	$COMPUTER_LOCAL_IP = array("192.168.1.1","192.168.1.12");
	
	//Puerto usado para el apagado remoto
	//Software a instalar para poder apagar el equipo remotamente http://www.ireksoftware.com/SleepOnLan/
	$COMPUTER_SLEEP_CMD_PORT = 7760;
	
	//Comando usado por la herramienta sleeponlan para apagar el equipo. 
	//Las opciones son: suspend, hibernate, logoff, poweroff, forcepoweroff, lock, reboot
	//Puedes crear una tarea para arrnacar automáticamente sleeponlan.exe al arrancar con los siguientes parámetros: /auto /port=7760
	$COMPUTER_SLEEP_CMD = "poweroff";
	
	//Carpeta en la que se encunetran los estilos Bootstrap. Por defecto = "" (Mismo directorio que el resto de archivos)
	//El directorio debe llamarde "bootstrap".
	//Si la carpeta está en un directorio superior pondremos "../"
	//¿Dos directorios más arriba? Pon "../../"
	//etc...
	$BOOTSTRAP_LOCATION_PREFIX = "";
	
?>
