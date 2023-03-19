<?php
sleep(1);
require_once('Model.php');
//requeteVille.php?ville=Bo
//SELECT * FROM rletud.cities WHERE name LIKE 'Bo%' LIMIT 5
$ville = $_GET['ville'];

$tab = (new \App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository())->getCommune($ville);

echo json_encode($tab);
