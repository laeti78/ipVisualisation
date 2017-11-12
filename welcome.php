<!doctype html>
<html lang="en">
  <head>
    <title>Snorter </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<!--Functions php -->
	<?php require "f.php"; 
		// On démarre la session AVANT d'écrire du code HTML
		session_start();?>
  </head>
  
  <body>
	<!-- Affiche bar de navigation -->
	<?php navbar(); ?> 
	<!-- Affiche Adresse IP -->
	<?php $_SESSION['IpLocale']=RechercheIpLocale(); ?> 
	
	<main role="main" class="container">
      <div class="jumbotron">
		<div align = "center"> 
			<h1>Les graphes</h1>
			<p class="lead">Voici les différents exemples de graphes disponibles pour visualiser votre réseau local.</p>
			<p>Ce site étant en développement, vous devez d'abord faire une capture wireshark sur votre poste et l'enregistrer en fichier texte en le nommant "Capture.txt".</p>
			<?php menu(); ?> 
		</div>
      </div>
    </main>
	
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  </body>
</html>
