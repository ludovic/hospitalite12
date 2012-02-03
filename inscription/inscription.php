<?php

switch($_POST['fonction']) {
	case "Aff": 
		$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
		mysql_select_db("db356768667");
		mysql_query("SET NAMES 'utf8'");	
		
		$rs->nouveau = 1;
		$rs->id_profession_sante = 0;
		$rs->complet = 1;
		$rs->arr_incomplet = "25/08/2012";
		$rs->ret_incomplet = "28/08/2012";
		$rs->transport = 1;
		$rs->id_gare = 0;
		$rs->reserve = 1;
		$rs->jeune = 0;
		$rs->heb=0;
		$rs->indiv; ?>
		<form name="frm_ins" id="frm_ins" method="post" action="inscription.php">
        	
			<input type="hidden" name="id_web" id="id_web" value="<?php echo intval($_POST["id"]);?>" />
			<input type="hidden" name="id_personne" id="id_personne" value="0" />
			<input type="hidden" name="fonction" id="fonction" value="eng" />
			<input type="hidden" name="id_pele" id="id_pele" value="" />
			<input type="hidden" name="step" id="step" value="1" />
            <div id="div_step1">
                <label for="nouveau">Nouveau</label>
                <div class="radio_ON">
                    <input type="radio" name="nouveau" id="non" value="0" <?php echo (!$rs->nouveau) ? 'checked="checked"' : '';?>/><span>Non</span>
                    <input type="radio" name="nouveau" id="oui" value="1" <?php echo ($rs->nouveau) ? 'checked="checked"' : '';?>/><span>Oui</span>
                </div>            
                <label for="nom">Nom*</label>
                <input id="nom" name="nom" class="txtitem" maxlength="50" value="<?php echo $rs->nom;?>" /><br />
                <label for="prenom">Prénom*</label>
                <input id="prenom" name="prenom" class="txtitem" maxlength="50" value="<?php echo $rs->prenom;?>" /><br />
                <label for="date_nais">Née le*</label>
                <input id="date_nais" name="date_nais" maxlength="10" value="<?php echo $rs->date_nais;?>" style="width: 100px; float: left;" /><br />
            </div>
            <div id="div_step2">
                <label for="adresse">Adresse*</label>
                <input id="adresse" name="adresse" class="txtitem" maxlength="50" value="<?php echo $rs->adresse;?>" /><br />
                <label for="adresse2">&nbsp;</label>
                <input id="adresse2" name="adresse2" class="txtitem" maxlength="50" value="<?php echo $rs->adresse2;?>" /><br />
                <label for="ville">Ville*</label>
                <input id="cp" name="cp" class="txtitem" maxlength="5" value="<?php echo $rs->cp;?>" />
                <input id="ville" name="ville" class="txtitem" maxlength="50" value="<?php echo $rs->ville;?>" /><br />
                <label for="tel">Téléphone</label>
                <input id="tel" name="tel" class="txtitem" maxlength="14" value="<?php echo $rs->tel;?>" /><br />
                <label for="mobile">Portable</label>
                <input id="mobile" name="mobile" class="txtitem" maxlength="14" value="<?php echo $rs->mobile;?>" /><br />
                <label for="email">Courriel*</label>
                <input id="email" name="email" class="txtitem" maxlength="50" value="<?php echo $rs->email;?>" /><br />
                <label for="secteur">Secteur</label>
                <select name="id_secteur" id="id_secteur" size="1">
                    <option value="0">Faites votre sélection...</option>
    <?php			$db1 = mysql_query("SELECT id_secteur, section FROM secteur ORDER BY section");
    
                while($rs1 = mysql_fetch_object($db1)) {
                    echo "\t\t\t\t<option value='".$rs1->id_secteur."'".(($rs->id_secteur == $rs1->id_secteur) ? " selected='selected'" : "" ).">".$rs1->section."</option>\n";
                }?>
                </select><br />
                <label for="id_profession_sante">Prof. Santé</label>
                <select name="id_profession_sante" id="id_profession_sante" size="1">
    <?php			$db1 = mysql_query("SELECT id_profession_sante, Profession FROM profession_sante ORDER BY Profession");
    
                while($rs1 = mysql_fetch_object($db1)) {
                    echo "\t\t\t\t<option value='".$rs1->id_profession_sante."'".(($rs->id_profession_sante == $rs1->id_profession_sante) ? " selected='selected'" : "" ).">".$rs1->Profession."</option>\n";
                }?>
                </select>
            </div>
            <hr class="sep"/>
            <div id="div_step3">
                <label for="complet" id="lbl_complet">Pélerinage Complet</label>
                <div class="radio_ON" id="div_complet">
                    <input type="radio" name="complet" id="non" value="0" <?php echo (!$rs->complet) ? 'checked="checked"' : '';?>/><span>Non</span>
                    <input type="radio" name="complet" id="oui" value="1" <?php echo ($rs->complet) ? 'checked="checked"' : '';?>/><span>Oui</span>
                </div>
                <div id="incomplet" style="clear:both;">
                    <label for="arr_incomplet" style="margin-left: 30px; width: 45px;">Du</label>
                    <input id="arr_incomplet" name="arr_incomplet" class="txtitem" maxlength="10" value="<?php echo $rs->arr_incomplet;?>" style="width: 100px; float: left;"/>
                    <label for="arr_incomplet" style="clear:none; width:30px; margin-left:30px;">au</label>
                    <input id="ret_incomplet" name="ret_incomplet" class="txtitem" maxlength="10" value="<?php echo $rs->ret_incomplet;?>" style="width: 100px; float:left;"/>
                </div>
                <label for="transport">Vous venez</label>
                <div class="radio_ON" id='div_transport'>
                    <input type="radio" name="transport" id="voiture" value="0" <?php echo (!$rs->transport) ? 'checked="checked"' : '';?> prix="25"/><span>en voiture (25€)</span>
                    <input type="radio" name="transport" id="car" value="1" <?php echo ($rs->transport) ? 'checked="checked"' : '';?> prix="65"/><span>en car (65€)</span>
                </div>
                <div id='div_gare' style="clear:both;">
                    <label for="gare">Monté à</label>
                    <select name="id_gare" id="id_gare" size="1">
        <?php			$db1 = mysql_query("SELECT id_gare, nom FROM gare ORDER BY nom");
        
                    while($rs1 = mysql_fetch_object($db1)) {
                        echo "\t\t\t\t<option value='".$rs1->id_gare."'".(($rs->id_gare == $rs1->id_gare) ? " selected='selected'" : "" ).">".$rs1->nom."</option>\n";
                    }?>
                    </select>
                </div>
            </div>
            <hr class="sep"/>
            <div id="div_step4">
                <label for="reserve" id="lbl_reserve">Désirez-vous que l'hospitalité vous réserve l'hotel :</label>
                <div class="radio_ON" id="div_reserve">
                    <input type="radio" name="reserve" id="non" value="0" <?php echo (!$rs->reserve) ? 'checked="checked"' : '';?>/><span>Non</span>
                    <input type="radio" name="reserve" id="oui" value="1" <?php echo ($rs->reserve) ? 'checked="checked"' : '';?>/><span>Oui</span>
                </div>
                <div id="div_reserve_ok">
                    <label for="jeune" id="lbl_jeune">Forfait jeune - Lycéen, étudiant seulement (95€)</label>
                    <div class="radio_ON" id="div_jeune">
                        <input type="radio" name="jeune" id="non" value="0" <?php echo (!$rs->jeune) ? 'checked="checked"' : '';?>/><span>Non</span>
                        <input type="radio" name="jeune" id="oui" value="1" <?php echo ($rs->jeune) ? 'checked="checked"' : '';?> prix="95"/><span>Oui</span>
                    </div>
                    <div id="div_hebergement" style="height: 180px;">
                        <label for="hebergement" id="lbl_hebergement">Hébergement désiré</label>
                        <div class="radio_ON" id="div_heb">
                            <input type="radio" name="hebergement" id="ASM" value="0" <?php echo $rs->heb == "0" ? 'checked="checked"' : '';?> prix="100"/><span>Abris Saint Michel - 100€</span><br/>
                            <input type="radio" name="hebergement" id="HOS" value="1" <?php echo $rs->heb == "1" ? 'checked="checked"' : '';?> prix="110"/><span>Hospitalet - 110€</span><br/>
                            <input type="radio" name="hebergement" id="ANG" value="2" <?php echo $rs->heb == "2" ? 'checked="checked"' : '';?> prix="130"/><span>Angelic - 130€</span><br/>
                            <input type="radio" name="hebergement" id="DUA" value="3" <?php echo $rs->heb == "3" ? 'checked="checked"' : '';?> prix="110"/><span>Duchesse Anne - 110€</span><br/>
                            <input type="radio" name="hebergement" id="HEL" value="4" <?php echo $rs->heb == "4" ? 'checked="checked"' : '';?> prix="130"/><span>Hélianthe - 130€</span><br/>
                            <input type="radio" name="hebergement" id="ROC" value="5" <?php echo $rs->heb == "5" ? 'checked="checked"' : '';?> prix="135"/><span>Roc de Massabielle - 135€</span><br/>
                            <input type="radio" name="hebergement" id="AMB" value="6" <?php echo $rs->heb == "6" ? 'checked="checked"' : '';?> prix="135"/><span>Ambassadeurs - 135€</span><br/>
                            <input type="radio" name="hebergement" id="HEL" value="7" <?php echo $rs->heb == "7" ? 'checked="checked"' : '';?> prix="150"/><span>Helgon - 150€</span><br/>
                        </div>
                        <label for="indiv" id="lbl_indiv">Chambre individuelle</label>
                        <div class="radio_ON" id="div_indiv">
                            <input type="radio" name="indiv" id="non" value="0" <?php echo (!$rs->indiv) ? 'checked="checked"' : '';?> prix="0" prix2="0"/><span>Non</span>
                            <input type="radio" name="indiv" id="oui" value="1" <?php echo ($rs->indiv) ? 'checked="checked"' : '';?> prix="60" prix2="18"/><span>Oui - 60 € (78€ pour Helgon)</span>
                        </div>
                    </div>
                    <label for="avecqui" id="lbl_avecqui">Avec qui aimeriez-vous loger? si possible,:</label>
                    <input id="avecqui" name="avecqui" class="txtitem" maxlength="50" value="<?php echo $rs->avecqui;?>" />
                </div>
                <div id="div_reserve_ko">
                    <label for="heb2" id="lbl_heb2">Nom de votre hébergement:</label>
                    <input id="heb2" name="heb2" class="txtitem" maxlength="50" value="<?php echo $rs->heb2;?>" />
                </div>
                <div style="clear:both">
                    <label for="total" id="lbl_total">TOTAL à payer: </label>
                    <input id="total" name="total" class="txtitem" maxlength="50" value="0,00 €" />
                </div>
            </div>
            <hr class="sep"/>
            <div id="div_step5">
                <label for="id_affectation" id="lbl_aff">Affectation souhaitée</label>
                <select name="id_affectation" id="id_affectation" size="1">
                    <option value="0">Faites votre sélection...</option>
    <?php			$db1 = mysql_query("SELECT id_affectation, Service FROM affectation WHERE id_affectation <> 0 ORDER BY Service");
    
                while($rs1 = mysql_fetch_object($db1)) {
                    echo "\t\t\t\t<option value='".$rs1->id_affectation."'".(($rs->id_affectation == $rs1->id_affectation) ? " selected='selected'" : "" ).">".$rs1->Service."</option>\n";
                }?>
                </select><br />
                <label for="aff_non" id="lbl_aff_non">Affectation que vous ne pouvez assumer: </label>
                <input id="aff_non" name="aff_non" class="txtitem" maxlength="50" value="<?php echo $rs->aff_non;?>" /><br />
                <label for="nuit" id="lbl_nuit">Souhaitez-vous faire une nuit ?</label>
                <div class="radio_ON" id="div_nuit">
                    <input type="radio" name="nuit" id="non" value="0" <?php echo (!$rs->nuit) ? 'checked="checked"' : '';?>/><span>Non</span>
                    <input type="radio" name="nuit" id="oui" value="1" <?php echo ($rs->nuit) ? 'checked="checked"' : '';?>/><span>Oui</span>
                </div><br />
                <div id="div_quellenuit">
                    <label for="quellenuit">Laquelle</label>
                    <div class="radio_ON" id="div_ckb_nuit">
                        <input type="checkbox" name="nuit1" id="nuit1" value="1" <?php echo ($rs->nuit1) ? 'checked="checked"' : '';?>/><span>1ère nuit</span>
                        <input type="checkbox" name="nuit2" id="nuit2" value="1" <?php echo ($rs->nuit2) ? 'checked="checked"' : '';?>/><span>2ème nuit</span>
                        <input type="checkbox" name="nuit3" id="nuit3" value="1" <?php echo ($rs->nuit3) ? 'checked="checked"' : '';?>/><span>3ème nuit</span>
                    </div>
                </div>
            </div>
            <hr class="sep"/>
            <div id="div_step6">
            	<label for="commentaires">Comentaires</label>
				<textarea name="commentaires" cols="100" rows="4" class="txtitem" id="commentaires"> </textarea>
            </div>                       
		</form>
<?php	mysql_free_result($db1);
		mysql_close($link);
		break;
	case "verif":
		header ("content-type: text/xml"); 
		echo "<root>\n"; 
		$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
		mysql_select_db("db356768667");
		mysql_query("SET NAMES 'utf8'");
		$tab = explode("/",$_POST["date_nais"]);
		$db1 = mysql_query("SELECT id_personne, adresse1, adresse2, CodePostal, commune, tel, portable, courriel, id_secteur, id_profession_sante
							FROM personne
							WHERE 	UCASE(nom) = '".strtoupper($_POST["nom"])."'
								and UCASE(prenom) = '".strtoupper($_POST["prenom"])."'
								and DateNaissance = '".$tab[2]."-".$tab[1]."-".$tab[0]."'");
		if($rs = mysql_fetch_object($db1))	{
			echo "\t<result>\n";
			echo "\t\t<id>".$rs->id_personne."</id>\n";
			echo "\t\t<adresse>".$rs->adresse1."</adresse>\n";
			echo "\t\t<adresse2>".$rs->adresse2."</adresse2>\n";
			echo "\t\t<CP>".$rs->CodePostal."</CP>\n";
			echo "\t\t<ville>".$rs->commune."</ville>\n";
			echo "\t\t<tel>".$rs->tel."</tel>\n";
			echo "\t\t<mobile>".$rs->portable."</mobile>\n";
			echo "\t\t<email>".$rs->courriel."</email>\n";
			echo "\t\t<secteur>".$rs->id_secteur."</secteur>\n";
			echo "\t\t<prof>".$rs->id_profession_sante."</prof>\n";
			echo "\t</result>\n";
		}
	    mysql_free_result($db1);
		mysql_close($link);
		echo "</root>";
		break;
	case "eng":
		$link = mysql_connect("db3046.1and1.fr", "dbo356768667", "+JsLiC2503+");
		mysql_select_db("db356768667");
		mysql_query("SET NAMES 'utf8'");
		$tab_nais = explode("/",$_POST["date_nais"]);
		$tab_arr = explode("/",$_POST["arr_incomplet"]);
		$tab_ret = explode("/",$_POST["ret_incomplet"]);
		
		$db = mysql_query("INSERT INTO insc_web (id_personne, id_pele, titre, nom, prenom, dateNaissance, adresse1, adresse2, CP, Commune, Tel, Portable, Courriel, id_secteur, id_profession_sante, nouveau, complet, dateArrivee, dateRetour, transport, id_gare, forfaitJeune, id_hebergement, desiderata, nomHeb, chambreInd, id_affectation, capacite_restrainte, nuit1, nuit2, nuit3, divers, paiementInsc, date)
							VALUES (
								".$_POST["id_personne"].",
								".$_POST["id_pele"].",
								'".$_POST["titre"]."',
								'".$_POST["nom"]."',
								'".$_POST["prenom"]."',
								'".$tab_nais[2]."-".$tab_nais[1]."-".$tab_nais[0]."',
								'".$_POST["adresse"]."',
								'".$_POST["adresse2"]."',
								'".$_POST["cp"]."',
								'".$_POST["ville"]."',
								'".$_POST["tel"]."',
								'".$_POST["mobile"]."',
								'".$_POST["email"]."',
								".$_POST["id_secteur"].",
								".$_POST["id_profession_sante"].",
								'".($_POST["nouveau"] ? "oui" : "non")."',
								'".($_POST["complet"] ? "oui" : "non")."',
								'".$tab_arr[2]."-".$tab_arr[1]."-".$tab_arr[0]."',
								'".$tab_ret[2]."-".$tab_ret[1]."-".$tab_ret[0]."',
								'".($_POST["transport"] ? "car" : "voiture")."',
								".$_POST["id_gare"].",
								'".($_POST["jeune"] ? "oui" : "non")."',
								".$_POST["hebergement"].",
								'".$_POST["avecqui"]."',
								'".$_POST["heb2"]."',
								'".($_POST["indiv"] ? "oui" : "non")."',
								".$_POST["id_affectation"].",
								'".$_POST["aff_non"]."',
								'".($_POST["nuit1"] ? "oui" : "non")."',
								'".($_POST["nuit2"] ? "oui" : "non")."',
								'".($_POST["nuit3"] ? "oui" : "non")."',
								'".$_POST["commentaires"]."',
								".floatval($_POST["total"]).",
								now())");
		$db = mysql_query("SELECT @@IDENTITY as id_web");
		if(!$db)	die("Une erreur est survenue. Merci de resaisir votre inscription svp ou nous contacter à admin@hospitalite12.fr");
		else 		echo "Merci";								
		mysql_free_result($db);
		mysql_close($link);
	default:
}
?>