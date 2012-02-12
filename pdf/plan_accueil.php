<?php
require('fpdf/fpdf.php');
ini_set("memory_limit",'16M');
setlocale(LC_ALL, "fr_FR");
	
class PDF extends FPDF	{	
	var $nbpage;
	var $datedeb;
	var $datefin;
	
	// Entete de page
	function Header()	{
		$this->Image('../assets/entete.jpg',18,10,0,25);
		$this->SetFont('Arial','',12);
		$this->SetXY(30,10);
		$this->Cell(240,8,'HOSPITALITE AVEYRONNAISE',0,2,'C');
		$this->SetFont('Arial','B',12);
		$this->Cell_utf8(240,8,'PELERINAGE à LOURDES du '.$this->datedeb.' au '.$this->datefin,0,2,'C');
		$this->Ln(6);
	}
	
	// Pied de page
	function Footer()	{
		$this->SetFont('Times','',5);
		$this->SetXY(10,-8);
		$this->Cell(280,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->SetXY(10,-8);
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
}

function get_nom($numero) {
	$db = mysql_query("	SELECT 	p.nom, p.prenom, m.codification
						FROM 	malade m,
								etre_malade em,
								personne p
						WHERE 	em.id_pele = ".intval($_GET["pele"])."
							and m.numero = ".intval($numero)."
							and m.id_malade = em.id_malade
							and p.id_personne = em.id_personne");
	while($tab[] = mysql_fetch_array($db));
	return $tab;
}

// Instanciation de la classe dérivée
$pdf = new PDF('P','mm','A3');
$pdf->AliasNbPages();
$pdf->SetAuthor("Hospitalité Aveyronnaise", false);
$pdf->SetCreator("Hospitalité Aveyronnaise", false);
$pdf->SetTitle("Plan - Accueil Notre Dame", false);
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

// X : Origine horizontale du block
// Y : Origine verticale du block
// O : Ordre des chambre
// B : Cesure du block
// N : Nouveau block (numéro du nouveau masque)
$db1 = mysql_query("SELECT * FROM plan_ACCND".($_GET["etg"] ? " WHERE Etage = ".$_GET["etg"] : ""));
while($rs = mysql_fetch_array($db1, MYSQL_ASSOC)) {
	$Tab_Masque[$rs["Etage"]][$rs["Masque"]] = $rs;
}

$query = "	SELECT 	distinct etage
			FROM 	module
			".($_GET["etg"] ? "WHERE etage = ".$_GET["etg"] : "")."
			ORDER BY etage";
			
$db = mysql_query($query);
while($rs = mysql_fetch_object($db)) {
	$pdf->AddPage();
	$pdf->SetXY(100,26);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell_utf8(100,8,'PLAN ACCUEIL NOTRE DAME : étage '.$rs->etage,1,1,'C');
	
	$pdf->SetX(10);
	$pdf->SetFont('Arial','',8);
	$db1 = mysql_query("SELECT 	*
						FROM 	module
						WHERE 	etage = ".intval($rs->etage));
	while($rs1 = mysql_fetch_object($db1))	{
		$row = 0;
		$pdf->SetXY($Tab_Masque[$rs->etage][$rs1->masque]["X"],$Tab_Masque[$rs->etage][$rs1->masque]["Y"]);
		$db2 = mysql_query("SELECT 	numero, libelle, num, lits
							FROM 	chambre
							WHERE 	hebergement = 'ACCND'
								AND etage = ".intval($rs->etage)."
								AND id_module = ".intval($rs1->id_module)."
							ORDER BY ordre ".$Tab_Masque[$rs->etage][$rs1->masque]["O"]);
		while($rs2 = mysql_fetch_object($db2)) {
			$b = intval($Tab_Masque[$rs->etage][$rs1->masque]["B"]);
			if($b && $row == mysql_num_rows($db2) - $b)	{
				$rs1->masque = intval($Tab_Masque[$rs->etage][$rs1->masque]["N"]);
				$pdf->Line($x, $pdf->GetY(),$x + 58, $pdf->GetY());
				$pdf->SetY(intval($Tab_Masque[$rs->etage][$rs1->masque]["Y"]));
			}
			$i = 1;
			$row++;
			$Tab_nom = get_nom($rs2->numero);
			$x = intval($Tab_Masque[$rs->etage][$rs1->masque]["X"]);
			$y = intval($pdf->GetY());
			$pdf->SetX($x);
			if($rs1->masque == 0) {		$pdf->SetX($x - 28);	$pdf->Cell_utf8(28,6,$rs2->libelle,1,0,"L");	}
			else 	$pdf->SetX($x);
			
			$pdf->Cell_utf8(5, 6, $Tab_nom[$i-1]["codification"], "LT", 0, "L");
			$pdf->Cell_utf8(48, 6, $Tab_nom[$i-1]["nom"]." ".$Tab_nom[$i-1]["prenom"], "T", 0, "L");
			$pdf->Cell_utf8(5, 6, $i++, "TR", 1, "R");
			for($i; $i <= $rs2->lits; $i++) {
				if($rs1->masque == 0) {		$pdf->SetX($x - 28);	$pdf->Cell_utf8(28,6,$rs2->libelle,1,0,"L");	}
				else 	$pdf->SetX($x);
				$pdf->Cell_utf8(5, 6, $Tab_nom[$i-1]["codification"], "L", 0, "L");
				$pdf->Cell_utf8(48, 6, $Tab_nom[$i-1]["nom"]." ".$Tab_nom[$i-1]["prenom"], 0, 0, "L");
				$pdf->Cell_utf8(5, 6, $i, "R", 1, "R");
			}
			if($rs1->masque <> 0) {
				$pdf->SetXY($x + 58, $y);
				$pdf->Cell_utf8(10, 6 * $rs2->lits, $rs2->num, 0, 2, "L");
			}
		}
		$pdf->Line($x, $pdf->GetY(),$x + 58, $pdf->GetY());
	}
	$pdf->SetFont('Arial', 'B', 25);
	$pdf->SetXY(180, 160);
	$pdf->Cell(35,0,"A",0,0,"C");
	$pdf->SetXY(80, 280);
	$pdf->Cell(55,0,"B",0,0,"C");
	$pdf->SetDrawColor(200);
	$pdf->line(117, 148, 135, 230);
	$pdf->line(193, 260, 215, 291);
	mysql_free_result($db2);
	mysql_free_result($db1);
}
mysql_free_result($db);
mysql_close($link);
$pdf->Output("Plan_ACCND.pdf","I");
?>