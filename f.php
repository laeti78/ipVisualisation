<?php
 function menu() {
	  echo'<a href="mapping_network.php" > Graph mouvable </a>'; 
  };
  
 function navbar() { ?>
      <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <a class="navbar-brand" href="#">The Snorter.v1.1</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Graphe <span class="sr-only">(current)</span></a>
          </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>
	<?php }; ?>
<!-- Affiche Adresse IP -->
	<?php function RechercheIpLocale() { ?> 
		<div align = "center"> 
		<?php $localIP = getHostByName(getHostName());	
		echo "Votre IP locale est ". $localIP; ?>
		</div>
		
	<?php return $localIP; 
	}; ?>