<?php
require('fpdf/fpdf.php');
ini_set("memory_limit",'16M');
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
		$this->Cell_utf8(150,8,'PELERINAGE à LOURDES du '.$this->datedeb.' au '.$this->datefin,0,2,'C');
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
		$txt = str_replace('â€"',"-",$txt);
		$txt = str_replace("Ã¨","è",$txt);
		$txt = str_replace("Ã©","é",$txt);
		$txt = str_replace("Ã?Â©","é",$txt);
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
$pdf->SetTitle("Hebergements - Accueil Notre Dame", false);
$pdf->SetAutoPageBreak(true, 10);

$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
mysql_select_db("db356768667");
mysql_query("SET NAMES 'utf8'");
mysql_query("SET lc_time_names = 'fr_FR'");

$db = mysql_query("	SELECT 	DATE_FORMAT(Debut,'%d') as datedeb, DATE_FORMAT(Fin,'%d %M %Y') as datefin
					FROM 	pele
					where id_pele = ".intval($_GET["pele"]));
$rs = mysql_fetch_object($db);
$pdf->datedeb = $rs->datedeb;
$pdf->datefin = $rs->datefin;

$db = mysql_query("	SELECT 	*
					FROM 	hebergement
					WHERE 	type = 'hospitalier'
						and id_hebergement > 1
						and id_hebergement IN (
							SELECT id_hebergement_retenu
							FROM inscrire
							WHERE id_pele = ".$_GET["pele"].")".
								($_GET["id"] ? "and id_hebergement = ".$_GET["id"] : "")."
					ORDER BY libelle");
while($rs = mysql_fetch_object($db)) {
	
	// Entete de page
	$pdf->AddPage();
	$pdf->SetXY(55,26);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell_utf8(100,8,$rs->Libelle,1,1,'C');
	
	if($rs->Telephone)	$pdf->Cell(0, 6, "Téléphone : ".$rs->Telephone, 0, 1, "L");
	if($rs->Fax)		$pdf->Cell(0, 6, "Fax : ".$rs->Fax, 0, 1, "L");
	if($rs->Email)		$pdf->Cell(0, 6, "Courriel : ".$rs->Email, 0, 1, "L");
	
	
	$pdf->Ln(3);		
    $pdf->SetFillColor(200,220,255);
	$pdf->Cell(6, 6, "", 1, 0, "C", true);
	$pdf->Cell(65, 6, "Hospitalier(e)", 1, 0, "C", true);
	$pdf->Cell_utf8(15, 6, "Age", 1, 0, "C", true);
	$pdf->Cell_utf8(85, 6, "Desiderata", 1, 0, "C", true);
	$pdf->Cell_utf8(18, 6, "Secteur", 1, 0, "C", true);
	$pdf->Ln(6);
	
	$db1 = mysql_query("SELECT 	p.titre,
								p.nom,
								p.prenom,
								(YEAR(pe.Debut)-YEAR(p.DateNaissance)) - (RIGHT(DATE(pe.Debut),5)<RIGHT(p.DateNaissance,5)) as age,
								p.DateNaissance,
								h.desiderata,
								s.section
						FROM 	etre_hospitalier eh,
								personne p,
								inscrire i,
								hebergement hb,
								pele pe,
								secteur s,
								hospitalier h
						WHERE 	hb.id_hebergement = ".intval($rs->id_hebergement)."
							and eh.id_pele = ".$_GET["pele"]."
							and eh.id_personne = p.id_personne
							and eh.id_pele = i.id_pele
							and eh.id_personne = p.id_personne
							and	i.id_hebergement_retenu = hb.id_hebergement
							and i.id_personne = p.id_personne
							and pe.id_pele = eh.id_pele
							and p.id_secteur = s.id_secteur
							and h.id_hospitalier = eh.id_hospitalier
						ORDER BY p.nom, p.prenom");
	$pdf->SetFont("Arial", "", 10);
    $pdf->SetFillColor(255,255,255);
	while($rs1 = mysql_fetch_object($db1)) {
		$pdf->Cell(6, 6, "", 1, 0, "C");
		$pdf->Cell_utf8(65, 6, $rs1->titre." ".$rs1->nom." ".$rs1->prenom, 1, 0, "L");
		$pdf->Cell(15, 6, $rs1->age, 1, 0, "C");
		$pdf->Cell_utf8(85, 6, substr($rs1->desiderata,0,55), 1, 0, "L");
		$pdf->Cell_utf8(18, 6, substr($rs1->section,0,9), 1, 0, "L", true);
		$pdf->Cell(0, 6, " ", "L", 1, "L", true);
	}
}
mysql_free_result($db);
mysql_close($link);
$pdf->Output("Hebergements.pdf","I");
?>