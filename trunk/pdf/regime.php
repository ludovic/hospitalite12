<?php
require('fpdf/fpdf.php');
ini_set("memory_limit",'16M');
setlocale(LC_ALL, "fr_FR");
	
class PDF extends FPDF	{
	// Pied de page
	function Footer()	{
		$this->SetFont('Times','',5);
		$this->SetXY(10,-10);
		$this->Cell(280,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->SetXY(10,-10);
		$this->Cell(280,5,utf8_decode('Imprimé le ').strftime("%d %B %Y a %H:%M",mktime()),0,0,'R');
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
	
	function Colonne($w, $h, $txt, $border=0, $align='J', $fill=false) {
		$x = $this->GetX();
		$y = $this->GetY();
		parent::MultiCell($w, $h, utf8_decode($txt), $border, $align, $fill);
		$this->SetXY($x+$w, $y);
	}
}

function get_nom($numero) {
	$db = mysql_query("	SELECT 	p.nom, p.prenom, 
								m.reg_normal, 
								m.reg_diab, 
								m.reg_ss_sel, 
								m.reg_mix, 
								m.reg_pb_deglutition, 
								m.reg_eau_gef 
						FROM 	malade m,
								etre_malade em,
								personne p
						WHERE 	em.id_pele = ".intval($_GET["pele"])."
							and m.numero = ".intval($numero)."
							and m.id_malade = em.id_malade
							and p.id_personne = em.id_personne
						ORDER BY nom, prenom");
	while($tab[] = mysql_fetch_array($db));
	return $tab;
}

// Instanciation de la classe dérivée
$pdf = new PDF('P','mm','A3');
$pdf->AliasNbPages();
$pdf->SetAuthor("Hospitalité Aveyronnaise", false);
$pdf->SetCreator("Hospitalité Aveyronnaise", false);
$pdf->SetTitle("Tableau des régimes - Accueil Notre Dame", false);
$pdf->SetAutoPageBreak(true, 10);

$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
mysql_select_db("db356768667");
mysql_query("SET NAMES 'utf8'");
mysql_query("SET lc_time_names = 'fr_FR'");

$db = mysql_query("	SELECT 	distinct etage, ascenseur, SUBSTRING_INDEX(libelle,' ', 2) as cote
					FROM 	chambre
					WHERE 	hebergement = 'ACCND'
					".($_GET["etg"] ? " and etage = ".$_GET["etg"] : "")."
					".($_GET["asc"] ? " and ascenseur = ".$_GET["asc"] : "")."
						and numero NOT BETWEEN 130 and 145
					ORDER BY etage, ascenseur"); // 130 - 145 sont les lignes des docteurs et aumoniers
while($rs = mysql_fetch_object($db)) {
	$pdf->AddPage();

	// Entete de page
	unset($T1,$T2,$T3,$T4,$T5);
	$pdf->Image('../assets/entete.jpg',23,10,0,18);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(140,12);
	$pdf->MultiCell_utf8(110,5,"ACCUEIL NOTRE DAME\r\n".$rs->etage."ème ETAGE ".strtoupper($rs->cote)." ZONE ".$rs->ascenseur,0,"C");
	$pdf->Cell_utf8(0,5,"IMPORTANT Ce document est à remettre à votre arrivée à la responsable du service",0,1,"C");
	$pdf->Ln(1);
	$pdf->SetX(17);
	$pdf->SetFont('Arial','',6);
    $pdf->SetTextColor(255);
    $pdf->SetFillColor(50);
	$pdf->Colonne(12, 5, "N° de\r\nChambre", 1, "C", true);
	$pdf->Colonne(5, 10, "CA", 1, "C", true);
	$pdf->SetFont('Arial','B',8);
	$pdf->Colonne(68, 10, "NOM PRENOM", 1, "C", true);
    $pdf->SetFillColor(128);
	$pdf->Colonne(30, 10, "NORMAUX", 1, "C", true);
	$pdf->Colonne(30, 10, "DIABETIQUES", 1, "C", true);
	$pdf->Colonne(30, 10, "HEPATIQUES", 1, "C", true);
	$pdf->Colonne(30, 10, "SANS SEL", 1, "C", true);
	$pdf->Colonne(30, 5, "SANS SEL / SANS\r\nSUCRE", 1, "C", true);
	$pdf->MultiCell(30, 10, "AUTRES", 1, "C", true);
	
	$pdf->SetX(17);
	$pdf->Colonne(12, 6, " ", 1, "C", true);
	$pdf->Colonne(5, 6, " ", 1, "C");
	$pdf->SetFont('Arial','',6);
    $pdf->SetTextColor(0);
	$pdf->Colonne(68, 6, "Cochez le type de repas et le lieu de restauration du malade", 1, "C");
    $pdf->SetTextColor(255);
    $pdf->SetFillColor(80);
	$pdf->Colonne(15, 3, "SALLE A\r\nMANGER", 1, "C", true);
	$pdf->Colonne(15, 6, "CHAMBRES", 1, "C", true);
	$pdf->Colonne(15, 3, "SALLE A\r\nMANGER", 1, "C", true);
	$pdf->Colonne(15, 6, "CHAMBRES", 1, "C", true);
	$pdf->Colonne(15, 3, "SALLE A\r\nMANGER", 1, "C", true);
	$pdf->Colonne(15, 6, "CHAMBRES", 1, "C", true);
	$pdf->Colonne(15, 3, "SALLE A\r\nMANGER", 1, "C", true);
	$pdf->Colonne(15, 6, "CHAMBRES", 1, "C", true);
	$pdf->Colonne(15, 3, "SALLE A\r\nMANGER", 1, "C", true);
	$pdf->Colonne(15, 6, "CHAMBRES", 1, "C", true);
	$pdf->Colonne(15, 3, "SALLE A\r\nMANGER", 1, "C", true);
	$pdf->MultiCell(15, 6, "CHAMBRES", 1, "C", true);
	
	$impair = true;
	$db1 = mysql_query("SELECT	*
						FROM 	chambre
						WHERE 	hebergement = 'ACCND'
							and etage = ".intval($rs->etage)."
							and ascenseur = '".$rs->ascenseur."'
							and num > 99
						ORDER BY num");
	while($rs1 = mysql_fetch_object($db1))	{
		$pdf->SetX(17);
		$pdf->Cell(12,0.5,"","LR",0,"C",true);
		$pdf->Cell(5,0.5,"","LR",0,"C");
		$pdf->Cell(248,0.5,"","LR",1,"C");
		$malades = get_nom($rs1->numero);
		for($i=0; $i<$rs1->lits; $i++) {
			unset($val1,$val2,$val3,$val4,$val5);
			$pdf->SetX(17);
			$pdf->SetTextColor(255);
			$pdf->SetFont('Arial','',6);
			if($impair) {
				$pdf->Cell(6,4.8,$rs1->num,1,0,"C",true);
				$pdf->Cell(6,4.8,"",1,0,"C",true);
			} else {
				$pdf->Cell(6,4.8,"",1,0,"C");
				$pdf->Cell(6,4.8,$rs1->num,1,0,"C",true);				
			}
		    $pdf->SetTextColor(0);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(5,4.8,isset($malades[$i]["nom"]) ? "AD" : "",1,0,"C");
			$pdf->Cell(68,4.8,$malades[$i]["nom"]." ".$malades[$i]["prenom"],1,0,"L");
			if($malades[$i]["reg_normal"])	{	$val1 = "X";	$T1++;	}
			if($malades[$i]["reg_diab"] && !$malades[$i]["reg_ss_sel"])	{	$val2 = "X";	$T2++;	}
			if($malades[$i]["reg_ss_sel"] && !$malades[$i]["reg_diab"])	{	$val3 = "X";	$T3++;	}
			if($malades[$i]["reg_ss_sel"] && $malades[$i]["reg_diab"]) 	{	$val4 = "X";	$T4++;	}
			if($malades[$i]["reg_mix"] || $malades[$i]["reg_pb_deglutition"] || $malades[$i]["reg_eau_gef"])	{	$val5 = "X";	$T5++;	}
			$pdf->Cell(15,4.8,$val1,1,0,"C");
			$pdf->Cell(15,4.8,"",1,0,"C");
			$pdf->Cell(15,4.8,$val2,1,0,"C");
			$pdf->Cell(15,4.8,"",1,0,"C");
			$pdf->Cell(15,4.8,"",1,0,"C");
			$pdf->Cell(15,4.8,"",1,0,"C");
			$pdf->Cell(15,4.8,$val3,1,0,"C");
			$pdf->Cell(15,4.8,"",1,0,"C");
			$pdf->Cell(15,4.8,$val4,1,0,"C");
			$pdf->Cell(15,4.8,"",1,0,"C");
			$pdf->Cell(15,4.8,$val5,1,0,"C");
			$pdf->Cell(15,4.8,"",1,1,"C");
		}
		$impair = !$impair;
	}
	$pdf->SetX(17);
	$pdf->Cell(12,0.5,"","LR",0,"C",true);
	$pdf->Cell(5,0.5,"","LR",0,"C");
	$pdf->Cell(248,0.5,"","LR",1,"C");
	
	$pdf->SetX(17);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(17,4.8,"DOCTEUR PC",1,0,"C",true);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(68,4.8,"",1,0,"L");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,1,"C");
	
	$pdf->SetX(17);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(17,4.8,"AUMONIER PC",1,0,"C",true);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(68,4.8,"",1,0,"L");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,0,"C");
	$pdf->Cell(15,4.8,"",1,1,"C");
	
	$pdf->SetX(34);
	$pdf->Cell(248,0.5,"","LR",1,"C");
	
	$pdf->SetX(34);
	$pdf->SetTextColor(255);
	$pdf->Cell(68,5,"TOTAL",1,0,"C",true);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(15,5,intval($T1),1,0,"C");
	$pdf->Cell(15,5,"0",1,0,"C");
	$pdf->Cell(15,5,intval($T2),1,0,"C");
	$pdf->Cell(15,5,0,1,0,"C");
	$pdf->Cell(15,5,0,1,0,"C");
	$pdf->Cell(15,5,0,1,0,"C");
	$pdf->Cell(15,5,intval($T3),1,0,"C");
	$pdf->Cell(15,5,0,1,0,"C");
	$pdf->Cell(15,5,intval($T4),1,0,"C");
	$pdf->Cell(15,5,0,1,0,"C");
	$pdf->Cell(15,5,intval($T5),1,0,"C");
	$pdf->Cell(15,5,0,1,1,"C");
	
	$pdf->ln(2);
	$pdf->SetX(17);
	$Y=$pdf->GetY();
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(17,4.8,"DOCTEUR NUIT",1,0,"C");
	$pdf->Cell(68,4.8,"",1,1);
	$pdf->SetX(17);
	$pdf->Cell(17,4.8,"AUMONIER NUIT",1,0,"C");
	$pdf->Cell(68,4.8,"",1,1);
	$pdf->SetX(17);
	$pdf->Cell(17,4.8,"AUTRES",1,0,"C");
	$pdf->Cell(68,4.8,"",1,1);
	$pdf->SetX(17);
	$pdf->Cell(17,4.8,"",1,0);
	$pdf->Cell(68,4.8,"",1,1);
	$pdf->SetX(17);
	$pdf->Cell(17,4.8,"",1,0);
	$pdf->Cell(68,4.8,"",1,1);
	
	$pdf->SetXY(117, $Y);
	$pdf->Cell(10,4.8,"","LT",0);
	$pdf->Cell(65,4.8,"CODE POUR LA COLONE CA = CATEGORIE","TR",0);
	$pdf->Cell(75,4.8,"NOMBRE DE MALADES ADULTES","T",0);
	$pdf->Cell(15,4.8,(intval($T1) + intval($T2) + intval($T3) + intval($T4) + intval($T5)),1,1,"C");
	$pdf->SetX(117);
	$pdf->Cell(10,4.8,"","L",0);
	$pdf->Cell(65,4.8,"AD MALADE ADULTE","R",0);
	$pdf->Cell(75,4.8,"NOMBRE ENFANTS +2 A 12 ANS",0,0);
	$pdf->Cell(15,4.8,"",1,1);
	$pdf->SetX(117);
	$pdf->Cell(10,4.8,"","L",0);
	$pdf->Cell(65,4.8,"EN ENFANT DE + 2 ANS A 12 ANS","R",0);
	$pdf->Cell(75,4.8,"NOMBRE AUMONIER ET DOCTEUR PC",0,0);
	$pdf->Cell(15,4.8,"",1,1);
	$pdf->SetX(117);
	$pdf->Cell(10,4.8,"","L",0);
	$pdf->Cell(65,4.8,"BB ENFANT DE 0 A 2 ANS","R",0);
	$pdf->Cell(25,4.8,"",0,0);
	$pdf->SetFont('Arial','I',6);	
	$pdf->Cell(50,4.8,"TOTAL PC",0,0);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(15,4.8,"",1,1);
	$pdf->SetX(117);
	$pdf->Cell(10,4.8,"","LB",0);
	$pdf->Cell(65,4.8,"SE NUITS SEULEMENT","BR",0);
	$pdf->Cell(75,4.8,"NOMBRE ENFANT 0 A 2 ANS",0,0);
	$pdf->Cell(15,4.8,"",1,1);
	$pdf->SetX(192);
	$pdf->Cell(75,4.8,"NOMBRE AUMONIER ET DOCTEUR NUITS","L",0);
	$pdf->Cell(15,4.8,"",1,1);
	$pdf->SetX(192);
	$pdf->Cell(75,4.8,"AUTRES A PRECISER","LB",0);
	$pdf->Cell(15,4.8,"",1,1);
}
mysql_free_result($db);
mysql_close($link);
$pdf->Output("Regimes.pdf","I");
?>