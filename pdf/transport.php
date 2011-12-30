<?php
require('fpdf/fpdf.php');
ini_set("memory_limit",'16M');
define("SALLE", 11);
setlocale(LC_ALL, "fr_FR");
	
class PDF extends FPDF	{	
	var $nbpage;
	
	// Entete de page
	function Header()	{
		$this->Image('../assets/entete.jpg',10,10,0,20);
		$this->SetFont('Arial','',12);
		$this->SetXY(30,10);
		$this->Cell(150,8,'HOSPITALITE AVEYRONNAISE',0,2,'C');
		$this->SetFont('Arial','B',12);
		$this->Cell_utf8(150,8,'PELERINAGE à LOURDES du 26 au 29 août 2011',0,2,'C');
		$this->Ln(6);
	}
	
	// Pied de page
	function Footer()	{
		$this->SetFont('Times','',5);
		$this->SetXY(10,-8);
		$this->Cell(190,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->SetXY(10,-8);
		$this->Cell(190,5,utf8_decode('Imprimé le ').strftime("%d %B %Y a %H:%M",mktime()),0,0,'R');
	}
	
	function Cell_utf8($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
		$txt = str_replace("Ãª","ê",$txt);
		$txt = str_replace("Ã¨","è",$txt);
		$txt = str_replace("Ã©","é",$txt);
		$txt = str_replace('â€"',"-",$txt);
		parent::cell($w, $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
	}
	
	function MultiCell_utf8($w, $h, $txt, $border=0, $align='J', $fill=false) {
		parent::MultiCell($w, $h, utf8_decode($txt), $border, $align, $fill);
	}
}

// Instanciation de la classe dérivée
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->SetAuthor("Hospitalité Aveyronnaise", false);
$pdf->SetCreator("Hospitalité Aveyronnaise", false);
$pdf->SetTitle("Feuille de transport", false);
$pdf->SetAutoPageBreak(true, 10);

mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
mysql_select_db("db356768667");
mysql_query("SET NAMES 'utf8'");

$query = "	SELECT 	*
			FROM 	transport t
			WHERE	t.id_transport <> 0
				".($_GET["id"] ? "and t.id_transport = ".$_GET["id"] : "")." 
				and t.id_transport in (	SELECT pp.id_transport
										FROM passer_par pp
										WHERE pp.id_pele = ".$_GET["pele"]."
											".($_GET["gare"] ? "and pp.id_gare = ".$_GET["gare"] : "").")
			ORDER BY t.nom_transport";
$db = mysql_query($query);
while($rs = mysql_fetch_object($db)) {
	$pdf->AddPage();
	$pdf->SetXY(55,26);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell_utf8(100,8,'TRANSPORT '.(($_GET['ret']) ? 'RETOUR' : '').': '.$rs->nom_transport,1,1,'C');
	
	$pdf->SetX(10);
	if($_GET["gare"])	{
		$db1 = mysql_query("SELECT nom FROM gare g WHERE g.id_gare = ".$_GET["gare"]);
		$rs1 = mysql_fetch_object($db1);
		$pdf->SetFont("Arial", "B", 12);
		$pdf->Cell_utf8(190,7,'Gare de '.$rs1->nom,0,1,"C");
		mysql_free_result($db1);
	}
	$pdf->Ln(3);
	$Y0 = $pdf->GetY();
	$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Autocariste : ',0,0);
	$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs->compagnie,0,1);
		
	$db1 = mysql_query("SELECT 	nom, prenom
						FROM 	avoir_responsable ar,
								personne p
						WHERE 	ar.id_transport = ".$rs->id_transport."
							and	ar.id_pele = ".$_GET["pele"]."
							and ar.id_personne = p.id_personne");
	if($rs1 = mysql_fetch_object($db1))	{
		$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Responsable : ',0,0);
		$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,1);
		
	}
	mysql_free_result($db1);
	
	$db1 = mysql_query("SELECT 	nom, prenom
						FROM 	ide i,
								personne p
						WHERE 	i.id_transport = ".$rs->id_transport."
							and	i.id_pele = ".$_GET["pele"]."
							and i.id_personne = p.id_personne");
	if($rs1 = mysql_fetch_object($db1))	{
		$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'IDE : ',0,0);
		$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
		while($rs1 = mysql_fetch_object($db1))	{
			$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
		}
	$pdf->ln(0);
	}
	mysql_free_result($db1);
	
	$db1 = mysql_query("SELECT 	nom, prenom
						FROM 	animation a,
								personne p
						WHERE 	a.id_transport = ".$rs->id_transport."
							and	a.id_pele = ".$_GET["pele"]."
							and a.id_personne = p.id_personne");
	if($rs1 = mysql_fetch_object($db1))	{
		$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Animation : ',0,0);
		$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
		while($rs1 = mysql_fetch_object($db1))	{
			$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
		}
	$pdf->ln(0);
	}
	mysql_free_result($db1);
	
	$db1 = mysql_query("SELECT 	nom, prenom
						FROM 	bagages b,
								personne p
						WHERE 	b.id_transport = ".$rs->id_transport."
							and	b.id_pele = ".$_GET["pele"]."
							and b.id_personne = p.id_personne");
	if($rs1 = mysql_fetch_object($db1))	{
		$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Bagage : ',0,0);
		$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
		while($rs1 = mysql_fetch_object($db1))	{
			$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
		}
	$pdf->ln(0);
	}
	mysql_free_result($db1);
	$Y1 = $pdf->GetY();

	$pdf->SetY($Y0);
	$db1 = mysql_query("SELECT 	g.nom, 
								DATE_FORMAT(pp.heure_aller, '%H:%i') as heure, 
								count(i.id_personne) as nb_personne
						FROM 	gare g,
								passer_par pp,
								inscrire i
						WHERE 	pp.id_transport = ".$rs->id_transport."
							and g.id_gare = pp.id_gare
							and pp.id_pele = ".$_GET["pele"]."
							and pp.id_transport = ".(($_GET['ret']) ? 'i.id_transport_retour' : 'i.id_transport')."
							and pp.id_gare = i.id_gare
							and pp.id_pele = i.id_pele
						GROUP BY g.id_gare
						ORDER BY heure_aller ".(($_GET['ret']) ? 'DESC' : 'ASC')."");
	while ($rs1 = mysql_fetch_object($db1))	{
		$pdf->SetX(90);
		$pdf->Cell_utf8(11,6,(($_GET['ret']) ? '' : $rs1->heure),"R",0);
		$pdf->Cell_utf8(90,6,$rs1->nom." - ".$rs1->nb_personne." personnes",0,1);
	}
	
	$pdf->SetX(10);
	if($Y1 > $pdf->GetY())	$pdf->SetY($Y1);
	$pdf->ln(2);
	$pdf->SetFont("Arial", "B", 10);
    $pdf->SetFillColor(200,220,255);
	$pdf->Cell(6, 6, "", 1, 0, "C", true);
	$pdf->Cell(11, 6, "Heure", 1, 0, "C", true);
	$pdf->Cell(74, 6, "Gare", 1, 0, "C", true);
	$pdf->Cell_utf8(75, 6, "Pélerin", 1, 0, "C", true);
	$pdf->Cell(6, 6, "", 1, 1, "C", true);
	
	$db1 = mysql_query("SELECT 	g.nom as gare, 
								DATE_FORMAT(pp.heure_aller, '%H:%i') as heure, 
								p.titre,
								p.nom, 
								p.prenom,
								em.id_malade,
								eh.id_hospitalier,
								ep.id_pelerin
						FROM 	gare g,
								passer_par pp,
								inscrire i,
								personne p
						LEFT JOIN etre_malade em on (p.id_personne = em.id_personne and em.id_pele = ".$_GET["pele"].")
						LEFT JOIN etre_hospitalier eh on (p.id_personne = eh.id_personne and eh.id_pele = ".$_GET["pele"].")
						LEFT JOIN etre_pelerin ep on (p.id_personne = ep.id_personne and ep.id_pele = ".$_GET["pele"].")
						WHERE 	pp.id_transport = ".$rs->id_transport."
							and g.id_gare = pp.id_gare
							and pp.id_pele = ".$_GET["pele"]."
							and pp.id_transport = ".(($_GET['ret']) ? 'i.id_transport_retour' : 'i.id_transport')."
							and pp.id_gare = i.id_gare
							and pp.id_pele = i.id_pele
							and i.id_personne = p.id_personne
						ORDER BY heure_aller ".(($_GET['ret']) ? 'DESC' : 'ASC').", p.nom, p.prenom");
	$pdf->SetFont("Arial", "", 10);
	while($rs1 = mysql_fetch_object($db1)) {
		$pdf->Cell(6, 6, "", 1, 0);
		$pdf->Cell(11, 6, (($_GET['ret']) ? '' : $rs1->heure), 1, 0);
		$pdf->Cell_utf8(74, 6, $rs1->gare, 1, 0);
		$pdf->Cell_utf8(75, 6, $rs1->titre." ".$rs1->nom." ".$rs1->prenom, 1, 0);
		if($rs1->id_malade)			$pdf->Cell(6, 6, "M", 1, 0, "C");
		if($rs1->id_hospitalier)	$pdf->Cell(6, 6, "H", 1, 0, "C");
		if($rs1->id_pelerin)		$pdf->Cell(6, 6, "P", 1, 0, "C");
		$pdf->ln(6);
	}	
}
mysql_free_result($db);
$pdf->Output("Car_ha.pdf","I");
?>