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
		$txt = str_replace('â€"',"-",$txt);
		$txt = str_replace("Ã¨","è",$txt);
		$txt = str_replace("Ã©","é",$txt);
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

$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
mysql_select_db("db356768667");
mysql_query("SET NAMES 'utf8'");

$db = mysql_query("	SELECT 	*
					FROM 	equipe e,
							affectation a
					WHERE	a.id_affectation = e.id_affectation
						".($_GET["id"] ? " and e.id_equipe = ".$_GET["id"] : "")."
						".($_GET["aff"] ? " and a.id_affectation = ".$_GET["aff"] : "")."
						and e.id_equipe in (SELECT id_equipe
											FROM faire_parti fp
											WHERE id_pele = ".$_GET["pele"].") 
					ORDER BY a.service, e.libelle");
while($rs = mysql_fetch_object($db)) {
	$pdf->AddPage();
	$pdf->SetXY(55,26);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell_utf8(100,8,'EQUIPE '.$rs->libelle,1,1,'C');
	
	$pdf->SetX(10);
	$pdf->SetFont("Arial", "B", 12);
	$pdf->Cell_utf8(190,7,'Service '.$rs->Service,0,1,"C");

	$pdf->Ln(3);		
    $pdf->SetFillColor(200,220,255);
	$pdf->Cell(6, 6, "", 1, 0, "C", true);
	$pdf->Cell(65, 6, "Hospitalier(e)", 1, 0, "C", true);
	$pdf->Cell_utf8(15, 6, "Age", 1, 0, "C", true);
	$pdf->Cell_utf8(55, 6, "Hebergement", 1, 0, "C", true);
	$pdf->Cell_utf8(43, 6, "Secteur", 1, 0, "C", true);
	$pdf->Ln(6);
	
	$db1 = mysql_query("SELECT 	fp.role,
								p.titre,
								p.nom,
								p.prenom,
								(YEAR(pe.Debut)-YEAR(p.DateNaissance)) - (RIGHT(DATE(pe.Debut),5)<RIGHT(p.DateNaissance,5)) as age,
								p.DateNaissance,
								hb.id_hebergement,
								hb.libelle,
								h.nomHeb,
								s.section
						FROM 	faire_parti fp,
								etre_hospitalier eh,
								personne p,
								inscrire i,
								hospitalier h,
								hebergement hb,
								pele pe,
								secteur s
						WHERE 	fp.id_equipe = ".$rs->id_equipe."
							and fp.id_pele = ".$_GET["pele"]."
							and fp.id_pele = eh.id_pele
							and fp.id_hospitalier = eh.id_hospitalier
							and eh.id_personne = p.id_personne
							and fp.id_pele = i.id_pele
							and eh.id_personne = p.id_personne
							and	i.id_hebergement_retenu = hb.id_hebergement
							and fp.id_hospitalier = h.id_hospitalier
							and i.id_personne = p.id_personne
							and pe.id_pele = fp.id_pele
							and p.id_secteur = s.id_secteur
						ORDER BY p.nom, p.prenom");
	$pdf->SetFont("Arial", "", 10);
	while($rs1 = mysql_fetch_object($db1)) {
		$pdf->Cell(6, 6, "", 1, 0);
		$pdf->Cell_utf8(65, 6, $rs1->titre." ".$rs1->nom." ".$rs1->prenom, 1, 0);
		$pdf->Cell_utf8(15, 6, $rs1->age." ans", 1, 0, "C");
		$pdf->Cell_utf8(55, 6, ($rs1->id_hebergement > 1) ? $rs1->libelle : $rs1->nomHeb, 1, 0);
		$pdf->Cell_utf8(43, 6, $rs1->section, 1, 0);
		$pdf->ln(6);
	}
	mysql_free_result($db1);
}
mysql_free_result($db);
mysql_close($link);
$pdf->Output("Car_ha.pdf","I");
?>