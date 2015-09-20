<?php /*
Remote Wake/Sleep-On-LAN Server
https://github.com/sciguy14/Remote-Wake-Sleep-On-LAN-Server
Author: Jeremy E. Blum (http://www.jeremyblum.com)
Actualizado y traducido por Angel Ros para ITAdicts (https://itadicts.com)
License: GPL v3 (http://www.gnu.org/licenses/gpl.html)
*/ 

ob_start();
session_start();


//You should not need to edit this file. Adjust Parameters in the config file:
require_once('config.php');
registradoadmin();
//Set default computer
if (empty($_GET)) { header('Location: '. "./menu.php?computer=0"); exit; }

//Uncomment to report PHP errors.
error_reporting(E_ALL);
ini_set('display_errors', '1');
			
// Enable flushing
ini_set('implicit_flush', true);
ob_implicit_flush(true);
ob_end_flush();

?>

<!DOCTYPE html>
<html lang="ES" >
  <head>
    <title>Wake/Sleep-On-LAN Remoto</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Herramienta para encender o apagar equipos Windows desde una Raspberry Pi">
    <meta name="author" content="Jeremy Blum">
	<!-- Actualizado y traducido por Angel Ros para ITAdicts -->

    <!-- Le styles -->
    <link href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px !important;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 600px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/ico/favicon.png">
  </head>

  <body>

    <div class="container">
    	<form class="form-signin" method="post">
        	<h3 class="form-signin-heading">
			<?php
				//print_r($_POST); //Useful for POST Debugging
				$approved_wake = false;
				$approved_sleep = false;
						
						if (!isset ($_POST['submitbutton'])){
							
						}

						elseif ($_POST['submitbutton'] == "¡Despertar!")
						{
							$approved_wake = true;
						}
						elseif ($_POST['submitbutton'] == "¡Apagar!")
						{
							$approved_sleep = true;
						}
						else{}


				$selectedComputer = $_GET['computer'];

			 	echo "Wake/Sleep-On-LAN Remoto</h3>";
				if ($approved_wake) {
					echo "¡Despertando!";
				} elseif ($approved_sleep) {
					echo "¡Apagando!";
				} else {?>
                    <select name="computer" onchange="if (this.value) window.location.href='?computer=' + this.value">
                    <?php
                        for ($i = 0; $i < count($COMPUTER_NAME); $i++)
                        {
                            echo "<option value='" . $i;
                            if( $selectedComputer == $i)
							{
								echo "' selected>";
							}
                            else
							{
								echo "'>";
							}
							echo $COMPUTER_NAME[$i] . "</option>";
                
                        }
                    ?>
                    </select>

				<?php } ?>
			
           
            <?php

				
				if (!isset($_POST['submitbutton']) || (isset($_POST['submitbutton']) && !$approved_wake && !$approved_sleep))
				{
					echo "<h5 id='wait'>Comprobando estado del PC. Espera por favor...</h5>";
					$pinginfo = exec("ping -c 1 " . $COMPUTER_LOCAL_IP[$selectedComputer]);
	    				?>
	    				<script>
						document.getElementById('wait').style.display = 'none';
				        </script>
	   					<?php
					if ($pinginfo == "1 packets transmitted, 0 packets received, 100% packet loss")
						
					{
						$asleep = true;
						echo "<h5>" . $COMPUTER_NAME[$selectedComputer] . " parece que está apagado.</h5>";
					}
					else
					{
						$asleep = false;
						echo "<h5>" . $COMPUTER_NAME[$selectedComputer] . " parece que está encendido.</h5>";
					}
				}
				                
                $show_form = true;
                
                if ($approved_wake)
                {
                	echo "<p>Aceptado. Enviando comando WOL...</p>";
					exec ('wakeonlan ' . $COMPUTER_MAC[$selectedComputer]);
					echo "<p>Comando enviado. Esperando a que " . $COMPUTER_NAME[$selectedComputer] . " despierte...</p><p>";
					$count = 1;
					$down = true;
					while ($count <= $MAX_PINGS && $down == true)
					{
						echo "Ping " . $count . "...";
						$pinginfo = exec("ping -c 1 " . $COMPUTER_LOCAL_IP[$selectedComputer]);
						$count++;
						if ($pinginfo != "1 packets transmitted, 0 packets received, 100% packet loss"  )
							
						{
							$down = false;
							echo "<span style='color:#00CC00;'><b>¡Está vivo!</b></span><br />";
							echo "<p><a href='?computer=" . $selectedComputer . "'>Volver al inicio</a></p>";
							
							$show_form = false;
						}
						else
						{
							echo "<span style='color:#CC0000;'><b>Sigue apagado.</b></span><br />";
						}
						sleep($SLEEP_TIME);
					}
					echo "</p>";
					if ($down == true)
					{
						echo "<p style='color:#CC0000;'><b>¡FALLO!</b> " . $COMPUTER_NAME[$selectedComputer] . " no parece haber despertado... ¿Intentar de nuevo?</p><p>(O <a href='?computer=" . $selectedComputer . "'>Vuelve al inicio</a>.)</p>";
					}
				}
				elseif ($approved_sleep)
				{
					echo "<p>Aceptado. Enviando comando de apagado...</p>";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "http://" . $COMPUTER_LOCAL_IP[$selectedComputer] . ":" . $COMPUTER_SLEEP_CMD_PORT . "/" .  $COMPUTER_SLEEP_CMD);
					curl_setopt($ch, CURLOPT_TIMEOUT, 5);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					
					if (curl_exec($ch) === false)
					{
						echo "<p><span style='color:#CC0000;'><b>comando fallido:</b></span> " . curl_error($ch) . "</p>";
						echo "<p><a href='?computer=" . $selectedComputer . "'>Volver al inicio</a></p>";
					}
					else
					{
						echo "<p><span style='color:#00CC00;'><b>Comando enviado</b></span> Esperando a que " . $COMPUTER_NAME[$selectedComputer] . " se apague...</p><p>";
						$count = 1;
						$down = false;
						while ($count <= $MAX_PINGS && $down == false)
						{
							echo "Ping " . $count . "...";
							$pinginfo = exec("ping -c 1 " . $COMPUTER_LOCAL_IP[$selectedComputer]);
							$count++;
							if ($pinginfo == "1 packets transmitted, 0 packets received, 100% packet loss")
							{
								$down = true;
								echo "<span style='color:#00CC00;'><b>It's Asleep!</b></span><br />";
								echo "<p><a href='?computer=" . $selectedComputer . "'>Volver al inicio</a></p>";
								$show_form = false;
								
							}
							else
							{
								echo "<span style='color:#CC0000;'><b>Sigue despierto.</b></span><br />";
							}
							sleep($SLEEP_TIME);
						}
						echo "</p>";
						if ($down == false)
						{
							echo "<p style='color:#CC0000;'><b>¡FALLO!</b> " . $COMPUTER_NAME[$selectedComputer] . " parece que no se apaga... ¿Volver a intentar?</p><p>(O <a href='?computer=" . $selectedComputer . "'>Vuelve al inicio</a>.)</p>";
						}
					}
					curl_close($ch);
				}
	
                
                if ($show_form)
                {
            ?>
                    <?php if ( (isset($_POST['submitbutton']) && $_POST['submitbutton'] == "¡Despertar!") || (!isset($_POST['submitbutton']) && $asleep) ) {?>
					<input class="btn btn-large btn-primary" type="submit" name="submitbutton" value="¡Despertar!">
					<input type="hidden" name="submitbutton" value="¡Despertar!"/>  <!-- handle if IE used and enter button pressed instead of wake up button -->
 <?php } else { ?>
		                <input class="btn btn-large btn-primary" type="submit" name="submitbutton" value="¡Apagar!"/>
						<input type="hidden" name="submitbutton" value="¡Apagar!" />  <!-- handle if IE used and enter button pressed instead of sleep button -->
                    <?php } ?>	
	
			<?php
				}
			?>
		</form>
    </div> <!-- /container -->
    <script src="<?php echo $BOOTSTRAP_LOCATION_PREFIX; ?>bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
