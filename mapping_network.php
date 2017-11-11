<!DOCTYPE html>
<meta charset="utf-8">
<style>

.node circle {
  cursor: pointer;
  stroke: #3182bd;
  stroke-width: 1.5px;
}

.node text {
  font: 10px sans-serif;
  pointer-events: none;
  text-anchor: middle;
}

line.link {
  fill: none;
  stroke: #9ecae1;
  stroke-width: 1.5px;
}

h1 {
	display: block;
	margin-left:auto;
	margin-right: auto;
	width: 500px
}

label {
	background-color: lightgreen;
}

</style>

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//d3js.org/d3.v3.min.js"></script>
</head>

<header>
  <div class="column">
      <h1>Graphe des IP source et destination</h1>
	  <p> Choisir une ip source </p>
	  
     </div>
</header>
<body>

<!-- Récupération ip source et ip adresse -->
<?php

function getIp(){
	//ouverture de fichiers
	$CaptureOrig = fopen('Capture.txt', 'r');
	$CaptureTri = fopen("NetworkData1.txt", "a");
	file_put_contents('NetworkData1.txt', '');//Efface contenu du fichier si déjà rempli
	
	//recherche des ip dans le fichier
	while(!feof($CaptureOrig)){
	$line = fgets($CaptureOrig);	
	if(strpos($line, "Internet Protocol Version 4, ") !== FALSE)
	{
		$number = strpos($line, "Internet Protocol Version 4, ");
		$tab = explode(" ",$line); //tab[5] = SRC ; tab[7] = DST
		$tab[5]=substr($tab[5],0,-1); //Enlève la virgule à la fin
			
		fwrite($CaptureTri,$tab[5]);
		fwrite($CaptureTri,"|");
		fwrite($CaptureTri,$tab[7]);
				
	$Ip[] = [$tab[5], $tab[7]];
	}
};


fclose($CaptureOrig);
fclose($CaptureTri);

return $Ip;

}

function getIpSrc($Ip) {
$IpSrc =array();

	foreach ($Ip as $key => $ligneIp){
		//si l'adresse ip source existe dans le tableau des IP source :
		if (array_key_exists($ligneIp[0], $IpSrc)){
			//si l'adresse de destination n'est pas presente dans la liste
			if (!in_array($ligneIp[1], $IpSrc[$ligneIp[0]])){
				//ajout à la liste des IP de cette source
				$IpSrc[$ligneIp[0]][] = $ligneIp[1];
			}
		}else{
			//ajout d'une nouvelle ip
			$IpSrc[$ligneIp[0]]= [$ligneIp[1]];
		}
	}
	return $IpSrc;
}

function getIpExport($IpSrc){
	//conversion array php -> array compatible graphe JS
	$IpExport = array();
	//pour chaque source de la liste
	foreach ($IpSrc as $key => $ligneIpSrc){
		$cpt = 0; //compteur
		$ajout = null;
		//pour chaque destination
		foreach ($ligneIpSrc as $key2 => $children){
			//si premier passage -> creation complete
			if ($cpt == 0){
				$ajout=array('name' =>$key, 'children' => array());
				$ajout['children'][]=array('name' => $children, 'size' => 10000);
			}else{
				//sinon mise à jour
				$ajout['children'][]=array('name' => $children, 'size' => 10000);
			}	
			//sinon -> ajout aux childrens
			$cpt++;
		}
		$IpExport[]=$ajout;
	}
	return (json_encode($IpExport));
	
}

function setSelect($IpSrc){
	//ajout des options
	?> 
	<select id="select">
	<option value="" disabled selected>Choisir une ip</option>
	<option value="TOUTES">TOUTES</option>
	<?php
	forEach($IpSrc as $key => $element){
		?>
		<option value="<?php print $key; ?>" > <?php print $key; ?> </option>
		<?php
	}
}

//récuperation de la liste des ip source/destination contenues dans le fichier source
$Ip = getIp();
//tri des ip par source
$IPSrc = getIpSrc($Ip);
//exportation vers un array comptablie avec les graphes
$IpExport = getIpExport($IPSrc);
setSelect($IPSrc);



?>
<!-- Fin de Récupération ip source et ip adresse -->


<?php

// ??
$CaptureFin = fopen("NetworkData2.txt", "a");
file_put_contents('NetworkData2.txt', '');

fclose($CaptureFin);

?>


<!-- DEBUT Setup et affichage du graphe -->

<script>

function findChildren(child,ip) {
  return child.name === ip;
}

//gestion des event sur le formulaire
$('#select').change(function(){
	
	var ip = String($(this).val());
	if (ip == "TOUTES"){
		root = rootBackup;
		update();
	}else{
		rootBackup.children.forEach(function(element, index) {
			if (ip.localeCompare(element.name) == 0){
				root = element;
				update();
			}
		});
	}
});

//recuperation de l'export PHP -> JSON
var tableau = <?php echo $IpExport ?>; //tableau JSON -> js

//creation de l'objet root du graphe
var root = {
	name:"graphe de test", //nom du noeud principal
	children: tableau //ajout des enfants, tableau js
	};
	
var rootBackup = root;

//console.log('root genéré : ');
//console.log(root);

//variables par defaut
var width = 960,
    height = 500,
    root;

var force = d3.layout.force()
    .linkDistance(80)
    .charge(-120)
    .gravity(.05)
    .size([width, height])
    .on("tick", tick);

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

var link = svg.selectAll(".link"),
    node = svg.selectAll(".node");

//fonction d'importation par defaut
/*
d3.json("readme.json", function(error, json) {
  if (error) throw error;
  root = json;
  update();
});
*/

//MAJ du graphe à partir des infos ci dessus
update();
function update() {
  var nodes = flatten(root),
      links = d3.layout.tree().links(nodes);
	  
  // Restart the force layout.
  force
      .nodes(nodes)
      .links(links)
      .start();

  // Update links.
  link = link.data(links, function(d) { return d.target.id; });

  link.exit().remove();

  link.enter().insert("line", ".node")
      .attr("class", "link");

  // Update nodes.
  node = node.data(nodes, function(d) { return d.id; });

  node.exit().remove();

  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .on("click", click)
      .call(force.drag);

  nodeEnter.append("circle")
      .attr("r", function(d) { return Math.sqrt(d.size) / 10 || 4.5; });

  nodeEnter.append("text")
      .attr("dy", ".35em")
      .text(function(d) { return d.name; });

  node.select("circle")
      .style("fill", color);
}

function tick() {
  link.attr("x1", function(d) { return d.source.x; })
      .attr("y1", function(d) { return d.source.y; })
      .attr("x2", function(d) { return d.target.x; })
      .attr("y2", function(d) { return d.target.y; });

  node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
}

function color(d) {
  return d._children ? "#3182bd" // collapsed package
      : d.children ? "#c6dbef" // expanded package
      : "#fd8d3c"; // leaf node
}

// Toggle children on click.
function click(d) {
  if (d3.event.defaultPrevented) return; // ignore drag
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
  update();
}

// Returns a list of all nodes under the root.
function flatten(root) {
  var nodes = [], i = 0;

  function recurse(node) {
    if (node.children) node.children.forEach(recurse);
    if (!node.id) node.id = ++i;
    nodes.push(node);
  }

  recurse(root);
  return nodes;
}

</script>
<!-- FIN Setup et affichage du graphe -->
</body>