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
$pdf->SetTitle("Feuille de module", false);
$pdf->SetAutoPageBreak(true, 10);

$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
mysql_select_db("db356768667");
mysql_query("SET NAMES 'utf8'");

$db = mysql_query("	SELECT 	*
					FROM 	module m
					WHERE	".($_GET["id"] ? "m.id_module = ".$_GET["id"] : "1")."
						".(($_GET["etg"]) ? " and etage = ".$_GET["etg"] : "")."
					ORDER BY m.etage, m.libelle");
while($rs = mysql_fetch_object($db)) {
	
	$db1 = mysql_query("SELECT distinct eh.id_hospitalier, 
								em.id_malade
						FROM 	chambre c, 
								s_occuper so, 
								etre_hospitalier eh, 
								personne ph, 
								etre_malade em, 
								malade m, 
								personne pm
						WHERE 	c.id_module = ".$rs->id_module."
							".(($_GET["chambre"]) ? " and numero = ".$_GET["chambre"] : "")."		
							AND so.numero = c.numero
							AND so.id_hospitalier = eh.id_hospitalier
							AND eh.id_pele = ".$_GET['pele']."
							AND eh.id_personne = ph.id_personne
							AND m.numero = c.numero
							AND m.id_malade = em.id_malade
							AND em.id_pele = eh.id_pele
							AND em.id_personne = pm.id_personne");

	if(mysql_num_rows($db1)) {
		$pdf->AddPage();
		$pdf->SetXY(55,26);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell_utf8(100,8,'MODULE '.$rs->libelle." (".$rs->etage."° etage)",1,1,'C');
		
		mysql_free_result($db1);
		$pdf->Ln(3);
		$db1 = mysql_query("SELECT 	nom, prenom
							FROM 	responsable_module rm,
									personne p,
									etre_hospitalier eh
							WHERE 	rm.id_module= ".$rs->id_module."
								and	eh.id_pele = ".$_GET["pele"]."
								and eh.id_personne = p.id_personne
								and rm.id_hospitalier = eh.id_hospitalier");
		if($rs1 = mysql_fetch_object($db1))	{
			$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Responsable : ',0,0);
			$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
			while($rs1 = mysql_fetch_object($db1))	{
				$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
			}
		$pdf->ln(0);
		}
		mysql_free_result($db1);
		
		$db1 = mysql_query("SELECT 	nom, prenom
							FROM 	ide_module im,
									personne p,
									etre_hospitalier eh
							WHERE 	im.id_module= ".$rs->id_module."
								and	eh.id_pele = ".$_GET["pele"]."
								and eh.id_personne = p.id_personne
								and im.id_hospitalier = eh.id_hospitalier");
		if($rs1 = mysql_fetch_object($db1))	{
			$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Infirmière : ',0,0);
			$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
			while($rs1 = mysql_fetch_object($db1))	{
				$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
			}
		$pdf->ln(0);
		}
		mysql_free_result($db1);
		
		$db1 = mysql_query("SELECT 	nom, prenom
							FROM 	brancardier_module bm,
									personne p,
									etre_hospitalier eh
							WHERE 	bm.id_module= ".$rs->id_module."
								and	eh.id_pele = ".$_GET["pele"]."
								and eh.id_personne = p.id_personne
								and bm.id_hospitalier = eh.id_hospitalier");
		if($rs1 = mysql_fetch_object($db1))	{
			$pdf->SetFont("Arial", "B", 10);	$pdf->Cell_utf8(25,6,'Brancardier : ',0,0);
			$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
			while($rs1 = mysql_fetch_object($db1))	{
				$pdf->SetFont("Arial", "", 10);		$pdf->Cell_utf8(25,6,$rs1->prenom." ".$rs1->nom,0,2);
			}
		$pdf->ln(0);
		}
		mysql_free_result($db1);

		$pdf->Ln(3);
		$pdf->SetFont("Arial", "B", 10);
		$pdf->SetFillColor(200,220,255);
		$pdf->Cell(12, 6, "Num", 1, 0, "C", true);
		$pdf->Cell(60, 6, "Hospitalier(e)", 1, 0, "C", true);
		$pdf->Cell(60, 6, "Malades", 1, 0, "C", true);
		$pdf->SetFont("Arial", "", 9);
		$pdf->Ln(6);
		$Y2 = $pdf->GetY();
		
		$db1 = mysql_query("SELECT 	*
							FROM 	chambre c
							WHERE 	c.id_module = ".$rs->id_module."
								".(($_GET["chambre"]) ? " and numero = ".$_GET["chambre"] : "")."					
							ORDER BY numero");
		while($rs1 = mysql_fetch_object($db1)) {
				
			$db2 = mysql_query("SELECT 	p.nom, p.prenom
								FROM 	s_occuper so,
										etre_hospitalier eh,
										personne p
								WHERE 	so.numero = ".$rs1->numero."
									and so.id_hospitalier = eh.id_hospitalier
									and eh.id_pele = ".$_GET["pele"]."
									and eh.id_personne = p.id_personne
								ORDER BY nom, prenom");
								
			$db3 = mysql_query("SELECT 	p.nom, p.prenom
								FROM	etre_malade em,
										malade m,
										personne p
								WHERE 	m.numero = ".$rs1->numero."
									and m.id_malade = em.id_malade
									and em.id_pele = ".$_GET["pele"]."
									and em.id_personne = p.id_personne
								ORDER BY nom, prenom");
								
			$Y1 = $pdf->GetY();
			for($i=0; $i<((mysql_num_rows($db2) > mysql_num_rows($db3)) ? mysql_num_rows($db3) : mysql_num_rows($db3)); $i++) {
				if($i)	$pdf->Cell_utf8(12, 6, "", 1, 0);
				else	$pdf->Cell_utf8(12, 6, $rs1->num, 1, 0);
	
				if($rs2 = mysql_fetch_object($db2)) {
					$pdf->Cell_utf8(60, 6, $rs2->prenom." ".$rs2->nom, 1, 0);
					$Y2 = $pdf->GetY();
				} else {
					$pdf->Cell_utf8(60, 6, "", 1, 0);
				}
				
	//			$pdf->SetXY(72, $Y1);
				if($rs3 = mysql_fetch_object($db3)) 	$pdf->Cell_utf8(60, 6, $rs3->prenom." ".$rs3->nom, 1, 1);
				else 									$pdf->Cell_utf8(60, 6, "", 1, 1);
			}
			mysql_free_result($db2);
			mysql_free_result($db3);
			if($Y2 > $pdf->GetY())	$pdf->SetY($Y2);
			$pdf->ln(3);
		}
		mysql_free_result($db1);
	}
}
mysql_free_result($db);
mysql_close($link);
$pdf->Output("Module_ha.pdf","I");
?>