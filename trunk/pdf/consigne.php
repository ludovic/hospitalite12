<?php
require('fpdf/fpdf.php');
ini_set("memory_limit",'16M');
define("SALLE", 11);

class PDF extends FPDF	{	
	var $nbpage;
	
	// Pied de page
	function Footer()	{
		$this->SetY(-8);
		$this->SetFont('Times','',5);
		if($this->nbpage <= 1)
			$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'R');
	}
	
	function Cell_utf8($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
		$txt = str_replace("Ãª","ê",$txt);
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
$pdf->SetTitle("Feuille de consignes", false);
$pdf->SetAutoPageBreak(true, 10);

mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
mysql_select_db("db356768667");
mysql_query("SET NAMES 'utf8'");

$db = mysql_query(" SELECT 	p.Titre,
							p.Prenom,
							p.Nom,
							p.Adresse1,
							p.Adresse2,
							p.Adresse3,
							p.CodePostal,
							p.Commune,
							ps.Prenom as Prenom_res_sect,
							ps.Nom as Nom_res_sect,
							ps.Tel as Tel_res_sect,
							ps.Portable as Portable_res_sect,
							hb.id_hebergement as heb,
							hb.Libelle as heb_libelle,
							h.nomheb,
							h.id_affectation,
							h.id_hospitalier,
							t.id_transport,
							t.nom_transport,
							t.compagnie,
							g.nom as gare,
							px.TotalPaye,
							px.solde,
							date_format(pp.heure_aller, '%H:%i le %d/%m/%y') as heure_aller
					FROM 	personne p, 
							etre_hospitalier e, 
							hospitalier h,
							secteur s,
							personne ps,
							prix px,
							inscrire i
					LEFT JOIN hebergement hb on i. id_hebergement_retenu = hb.id_hebergement
					LEFT JOIN transport t on i.id_transport = t.id_transport
					LEFT JOIN gare g on i.id_gare = g.id_gare
					LEFT JOIN passer_par pp on 	i.id_transport = pp.id_transport 
											and i.id_gare = pp.id_gare
											and i.id_pele = pp.id_pele					
					WHERE 	e.id_pele = ".$_GET["pele"]."
						".($_GET["id"] ? "and e.id_personne = ".$_GET["id"] : "")." 
						".($_GET["sec"] ? "and s.id_secteur = ".$_GET["sec"] : "")." 
						and p.valid='XXX'
						and p.id_personne = e.id_personne
						and h.id_hospitalier = e.id_hospitalier 
						and p.id_secteur = s.id_secteur
						and s.id_personne_etre_resposable = ps.id_personne
						and i.id_pele = e.id_pele
						and i.id_personne = e.id_personne 
						and i.id_prix = px.id_prix
					ORDER BY p.nom, p.prenom");
$pdf->nbpage = mysql_num_rows($db);
while($rs = mysql_fetch_object($db)) {
	$pdf->AddPage();

	$pdf->Image('../assets/entete.jpg',10,10,0,20);
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY(30,10);
	$pdf->Cell(150,8,'HOSPITALITE AVEYRONNAISE',0,2,'C');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell_utf8(150,8,'PELERINAGE à LOURDES du 26 au 29 août 2011',0,0,'C');
	$pdf->Ln(12);

	$pdf->SetFont('Times','',10);
	$pdf->Cell_utf8(36,5,'Responsable diocésain : ',0,0);
	$pdf->Cell_utf8(0,5,'Florent FRAYSSE',0,1);
	$pdf->Cell_utf8(36,5,'Responsable de secteur : ',0,0);
	$pdf->Cell_utf8(0,5,$rs->Prenom_res_sect.' '.$rs->Nom_res_sect,0,1);
	$pdf->Cell_utf8(36);
	$pdf->Cell_utf8(0,5,'('.($rs->Tel_res_sect ? $rs->Tel_res_sect : $rs->Portable_res_sect).')',0,0);
	
	$pdf->SetXY(110,40);
	$pdf->SetFont('Times','B',10);
	$pdf->Cell_utf8(0,5, $rs->Titre.' '.$rs->Prenom.' '.$rs->Nom,0,2);
	$pdf->Cell_utf8(0,5,$rs->Adresse1,0,2);
	if($rs->Adresse2)	$pdf->Cell_utf8(0,5,$rs->Adresse2,0,2);
	if($rs->Adresse3)	$pdf->Cell_utf8(0,5,$rs->Adresse3,0,2);
	$pdf->Cell_utf8(0,5,$rs->CodePostal.' '.$rs->Commune,0,1);
	
	$pdf->SetY(60);
	$pdf->SetFont('Times','',10);
	$pdf->Cell_utf8(0,5,'Cher(e) ami(e),',0,1);
	$pdf->Cell_utf8(0,5,'Vous êtes bien inscrit au Pèlerinage 2011.',0,1);
	
	if($rs->id_transport)
		$pdf->Cell_utf8(0,5,'Départ : Le '.$rs->nom_transport.' de '.$rs->compagnie.' vous prendra à '.$rs->heure_aller.' à '.$rs->gare,0,1);
	else
		$pdf->Cell_utf8(0,5,'Vous avez signifié vouloir venir par vos propres moyens',0,1);
	
	$db1 = mysql_query("SELECT 	a.Service,
								a.id_affectation
						FROM 	obtenir o,
								affectation a
						WHERE 	o.id_hospitalier = ".$rs->id_hospitalier."
							and o.id_affectation = a.id_affectation");
								
	if (mysql_num_rows($db1) > 0) {
		$pdf->Cell_utf8(29,5,'Vous êtes affecté à',0,0);
		while($rs1 = mysql_fetch_object($db1)) {
			unset($txt_serv);
			$txt_serv = $rs1->Service;

			$db2 = mysql_query("SELECT 	e.id_equipe,
										e.libelle
								FROM 	faire_parti fp,
										equipe e
								WHERE 	fp.id_pele = ".$_GET["pele"]."
									and fp.id_hospitalier = ".$rs->id_hospitalier."
									and e.id_affectation = ".$rs1->id_affectation."
									and fp.id_equipe = e.id_equipe");
			$rs2 = mysql_fetch_object($db2);
			if($rs2->id_equipe)		$txt_serv .= " dans l'équipe ".$rs2->libelle;
			mysql_free_result($db2);
			
			if($rs1->id_affectation == SALLE) {
			
				$db2 = mysql_query("SELECT 	libelle 
									FROM 	responsable_module rm,
											module m
									WHERE	id_hospitalier = ".$rs->id_hospitalier."
										and rm.id_module = m.id_module");
				if(mysql_num_rows($db2))	{
					$rs2 = mysql_fetch_object($db2);
					$txt_serv = "Responsable du module ".$rs2->libelle;
				}
				mysql_free_result($db2);
				
				$db2 = mysql_query("SELECT 	libelle 
									FROM 	ide_module im,
											module m
									WHERE	id_hospitalier = ".$rs->id_hospitalier."
										and im.id_module = m.id_module");
				if(mysql_num_rows($db2))	{
					$rs2 = mysql_fetch_object($db2);
					$txt_serv = "Infirmière (DE) du module ".$rs2->libelle;
				}
				mysql_free_result($db2);
				
				$db2 = mysql_query("SELECT 	libelle 
									FROM 	brancardier_module bm,
											module m
									WHERE	id_hospitalier = ".$rs->id_hospitalier."
										and bm.id_module = m.id_module");
				if(mysql_num_rows($db2))	{
					$rs2 = mysql_fetch_object($db2);
					$txt_serv = "Brancardier en charge du module ".$rs2->libelle;
				}
				mysql_free_result($db2);
				
				$db2 = mysql_query("SELECT 	c.*,
											p.titre,
											p.nom,
											p.prenom
									FROM 	s_occuper so,
											chambre c,
											module m,
											responsable_module rm,
											etre_hospitalier eh,
											personne p
									WHERE	so.id_hospitalier = ".$rs->id_hospitalier."
										and so.numero = c.numero
										and c.id_module = m.id_module
										and m.id_module = rm.id_module
										and rm.id_hospitalier = eh.id_hospitalier
										and eh.id_personne = p.id_personne");
				if(mysql_num_rows($db2))	{
					while($rs2 = mysql_fetch_object($db2)) {
						$pdf->Cell_utf8(0,5,"Hospitalière en chambre ".$rs2->libelle." (".$rs2->etage.$rs2->ascenseur.") - Responsable de module : ".$rs2->prenom." ".$rs2->nom,0,2);
						$txt_serv = "";
					}
				}
				mysql_free_result($db2);
			}
			if($txt_serv)	$pdf->Cell_utf8(0,5,$txt_serv,0,2);
		}
		$pdf->ln(0);
	}
	else 
		$pdf->Cell_utf8(0,5,'Pas d\'affectation',0,1);
		
	mysql_free_result($db1);
	
	if(intval($rs->heb) > 1)
		$pdf->Cell_utf8(0,5,'L\'Hospitalité Aveyronnaise vous a réservé une chambre à : '.$rs->heb_libelle,0,1);
	else 
		$pdf->Cell_utf8(0,5,'Vous avez signifié vouloir vous occuper personnellement de votre réservation à '.$rs->nomheb,0,1);
		
	if($rs->solde > 0)
		$pdf->Cell_utf8(0,5,'Vous avez versé un acompte de '.$rs->TotalPaye.' euros il vous reste à régler '.$rs->solde.' euros avant le 25/08/2011',0,1);
	elseif($rs->solde == 0)
		$pdf->Cell_utf8(0,5,'Vous avez versé un acompte de '.$rs->TotalPaye.' euros votre pélerinage est donc soldé',0,1);
	else 
		$pdf->Cell_utf8(0,5,'Vous avez versé un acompte de '.$rs->TotalPaye.' euros il vous sera restitué '.$rs->solde.' euros',0,1);		
	
	$pdf->Cell_utf8(0,5,'à CHANTAL CONSTANS - LES CANS HAUTS - CEIGNAC - 12450 CALMONT',0,1);
	
	$pdf->SetY(106);
	$pdf->SetFont('Times','BI',18);
	$pdf->Cell_utf8(0,12,'«Avec Bernadette, prier le Notre Père»',0,1,"C");
	$pdf->SetY($pdf->GetY()-4);
	$pdf->SetFont('Times','B',15);
	$pdf->Cell_utf8(0,14,'Le départ du pèlerinage diocésain aura lieu le vendredi 26 août 2011',0,1,"C");
	
	$pdf->SetFont('Times','',11);
	$pdf->MultiCell_utf8(0,5,'- L\'ESPRIT : Tous bénévoles, rendons nous pleinement disponibles pour accompagner et servir nos frères malades, pèlerins et hospitaliers pour vivre dans la joie un grand moment de notre engagement chrétien.',0,'J');
	$pdf->Ln();
	$pdf->MultiCell_utf8(0,5,'- DISPONIBILITE : Compte tenu du grand nombre de personnes malades (300) et  afin de  leur éviter de longues périodes d\'attente  nous recommandons à tous (dames et messieurs) d\'être présents 1 heure avant le départ pour les cérémonies, indépendamment de votre affectation principale.',0,'J');
	$pdf->Ln();
	$pdf->MultiCell_utf8(0,5,'- REUNION GENERALE : 	Vendredi 26 août à 20h30, Salle Jean XXIII (2ème étage Forum, entrée face à la Librairie) Présence indispensable pour faire la connaissance de votre responsable de service.',0,'J');
	$pdf->Ln();
	$pdf->MultiCell_utf8(0,5,'- DEPART :  L\'horaire et le lieu sont inscrits en début de page. Si vous le pouvez, présentez-vous une ½ heure avant pour aider à l\'installation des personnes malades. Pour l\'aller : n\'oubliez pas d\'emporter un repas froid.',0,'L');
	$pdf->Ln();
	$pdf->MultiCell_utf8(0,5,'- LA TENUE Pour tous : de bonnes chaussures de marche et une tenue de pluie.',0,'J');
	$pdf->Cell(10,5,'*',0,0,'R');
	$pdf->MultiCell_utf8(0,5,'Hospitalières : Vous devez vous munir d\'une ou plusieurs blouses blanches et d\'une tenue de ville correcte. A Lourdes, pour une meilleure esthétique, évitez l\'association : blouse/pantalon ou jupe longue. Pour les cérémonies, la chasuble, le béret et le foulard (disponibles à la permanence avec achat) sont indispensables. Le béret est obligatoire au restaurant. En dehors des cérémonies ôtez la chasuble : des tabliers en plastique vous seront fournis.',0,'J');
	$pdf->Cell(10,5,'*',0,0,'R');
	$pdf->MultiCell_utf8(0,5,'Hospitaliers : Des tenues de ville sont exigées. La tenue des hospitaliers comprend : un pantalon sombre, un polo chemise ou chemisette blanche, ainsi qu\'un foulard et un béret disponibles à la permanence (achat)',0,'J');
	$pdf->Cell(10,5,'*',0,0,'R');
	$pdf->MultiCell_utf8(0,5,'Les jeunes et les Ados : Il est souhaité une tenue correcte pour les jeunes garçons et filles : épaules, cuisses et nombril couverts !!! Les jeunes filles sont dispensées de la chasuble et de la blouse. Tous affectés aux déplacements, ils adoptent la tenue des hospitaliers: tee-shirt blanc, foulard.',0,'J');
	$pdf->MultiCell_utf8(0,5,'N\'oubliez pas d\'emporter votre sac de couchage et votre casse croûte (pour le jour du départ).
	Pensez à bien identifier tous vos sacs et bagages avec les étiquettes qui vous seront fournies.
	Les mineurs qui n\'ont pas participé au pré pélé se rendront devant l\'Accueil Notre-Dame à proximité du Gave à 14h 15 où ils retrouveront les jeunes arrivés la veille.',0,'J');
	$pdf->Ln();
	$pdf->MultiCell_utf8(0,5,'- L\'ETIQUETAGE : (affaires des personnes malades et des hospitaliers) :  Tout doit être étiqueté (canne, sac à main, valise, manteau, chariot, poche de souvenirs…). Complétez les étiquettes avec toutes les mentions (Nom, prénom, adresse, n° bus, nom de l\'étage, et numéro de chambre) faites de même pour le retour. Vous pourrez',0,'J');
}
mysql_free_result($db);
$pdf->Output("ha.pdf",$pdf->nbpage <=1 ? "I" : "I");
?>