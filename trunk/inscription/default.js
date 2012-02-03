$(document).ready(function() {
// Creation et parametrage du PopUp
	$("#popup").dialog({
		autoOpen: false,
		height: "auto",
		width: 450,
		modal: true,
		position: 'middle',
		buttons: { 	"Annuler": function() {
						if(confirm("Souhaitez-vous reellement annuler votre inscription ?\n(La saisie des information ne sera pas enregistrer)")) {
							$(this).dialog("close");
						}
					 },
					 "Ok": function() {
						var bValid = true;

						bValid = bValid && verif_step1();
						bValid = bValid && verif_step2();
						bValid = bValid && verif_step3();
						bValid = bValid && verif_step4();
						bValid = bValid && verif_step5();
						
						if(bValid) 	{	$("#frm_ins").submit();	}
					 },
					 "Suivant": function() {
						 switch($("#step").val()) {
							case "1":
								if(verif_step1()) {
									charge_per();
									$("#step").val("2");
									$(".ui-dialog-buttonset button:eq(3)").show();
									$("#div_step2").show();
									$("#popup").dialog({title: "Inscription au pélerinage - Etape 2"});
								}
								break;
							case "2":
								if(verif_step2()) {
									$("#step").val("3");
									$("#div_step1").hide();
									$("#div_step2").hide();
									$("#div_step3").show();
									$("#popup").dialog({title: "Inscription au pélerinage - Etape 3"});
								}
								break;
							case "3":
								if(verif_step3()) {
									$("#step").val("4");
									$("#div_step3").hide();
									$("#div_step4").show();
									$("#popup").dialog({title: "Inscription au pélerinage - Etape 4"});
								}
								break;
							case "4":
								if(verif_step4()) {
									$("#step").val("5");
									$("#div_step4").hide();
									$("#div_step5").show();
									$("#popup").dialog({title: "Inscription au pélerinage - Etape 5"});
								}
								break;
							case "5":
								if(verif_step5()) {
									$("#step").val("6");
									$("#div_step5").hide();
									$("#div_step6").show();
									$("#popup").dialog({title: "Inscription au pélerinage - Etape 6"});
								}
								break;
							case "6":
								$("#step").val("7");
								$("#div_step1").show();
								$("#div_step2").show();
								$("#div_step3").show();
								$("#div_step4").show();
								$("#div_step5").show();
								$(".sep").show();
								$(".ui-dialog-buttonset button:eq(2)").hide();
								$(".ui-dialog-buttonset button:eq(1)").show();
								$("#popup").dialog({title: "Inscription au pélerinage - Résumé"});
							default:
						 }						 
					 },
					 "Précédent": function() {
						 switch($("#step").val()) {
							case "2":
								$("#step").val("1");
								$(".ui-dialog-buttonset button:eq(3)").hide();
								$("#div_step2").hide();
								$("#id_personne").val("0");
								$("#adresse").val("");
								$("#adresse2").val("");
								$("#cp").val("");
								$("#ville").val("");
								$("#tel").val("");
								$("#mobile").val("");
								$("#email").val("");
								$("#id_secteur").val("0");
								$("#id_profession_sante").val("0");								
								$("#popup").dialog({title: "Inscription au pélerinage - Etape 1"});
								break;
							case "3":
								$("#step").val("2");
								$("#div_step1").show();
								$("#div_step2").show();
								$("#div_step3").hide();
								$("#popup").dialog({title: "Inscription au pélerinage - Etape 2"});
								break;
							case "4":
								$("#step").val("3");
								$("#div_step3").show();
								$("#div_step4").hide();
								$("#popup").dialog({title: "Inscription au pélerinage - Etape 3"});
								break;
							case "5":
								$("#step").val("4");
								$("#div_step4").show();
								$("#div_step5").hide();
								$("#popup").dialog({title: "Inscription au pélerinage - Etape 4"});
								break;
							case "6":
								$("#step").val("5");
								$("#div_step5").show();
								$("#div_step6").hide();
								$("#popup").dialog({title: "Inscription au pélerinage - Etape 5"});
								break;
							case "7":
								$("#step").val("6");
								$("#div_step1").hide();
								$("#div_step2").hide();
								$("#div_step3").hide();
								$("#div_step4").hide();
								$("#div_step5").hide();
								$("#div_step6").show();
								$(".sep").hide();
								$(".ui-dialog-buttonset button:eq(2)").show();
								$(".ui-dialog-buttonset button:eq(1)").hide();
								$("#popup").dialog({title: "Inscription au pélerinage - Etape 6"});
								break;
							default:
						 }
					 }
				}
	});
	
// Création de la structure et overture du PopUp
	$.ajax({ 
		type : "POST",
		data : { fonction : "Aff",
				 pele : $("#pele").val() },
		dataType: "html",
		url : "inscription.php",
		async : false,
		global : false,
		cache : true,
		error : function(request,erreur,except){
			// En cas d'erreur			
			$("#popup").html("<div id='home_error'>Une erreur interne s'est produite<br/><br/>Merci de nous contacter (incription@hospitalite12.fr)</div>");
			$("#popup").dialog({title: "Erreur"});
			$(".ui-dialog-buttonset button:eq(1)").show();	
			$(".ui-dialog-buttonset button:eq(2), .ui-dialog-buttonset button:eq(3)").hide();
			$("#popup").dialog("open");
		},
		success : function (html) {
			// En cas de succes
			$("#popup").html(html);
			$("#id_pele").val($("#pele").val());
			$("#div_step2, #div_step3, #div_step4, #div_step5, #div_step6, .sep").hide();
			$(".ui-dialog-buttonset button:eq(1)").hide();
			$(".ui-dialog-buttonset button:eq(3)").hide();
			$("#popup").dialog({title: "Inscription au pélerinage - Etape 1"});
			$("#popup").dialog("open");
		}
	});
	
	if($("#popup").dialog("isOpen")) {
	// Au moment de l'ouverture :
		$('#div_complet input#non').click(function() { $("#incomplet").show(); recalcul(); });
		$('#div_complet input#oui').click(function() { $("#incomplet").hide(); recalcul(); });
		if($('#div_complet input[name="complet"]:checked').val() == "1") { $("#incomplet").hide(); }
		
		// Ami developpeur si tu veux comprendre les ligne ci-dessous rdv sur : http://jqueryui.com/demos/datepicker
		$("#date_nais").datepicker({
			dateFormat: "dd/mm/yy",
			showOn: "button",
			buttonImage: "img/calendar.gif",
			buttonImageOnly: true,
			autoSize: true,
			firstDay: 1,
			buttonText: 'Choisissez votre date...',
		});	
		
		var dates = $("#arr_incomplet, #ret_incomplet").datepicker({
			minDate : "25/08/2012",
			maxDate : "28/08/2012",
			dateFormat: "dd/mm/yy",
			changeMonth: false,
			showOn: "button",
			buttonImage: "img/calendar.gif",
			buttonImageOnly: true,
			autoSize: true,
			firstDay: 1,
			buttonText: 'Choisissez votre date...',
			onSelect: function( selectedDate ) {
				var option = this.id == "arr_incomplet" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
		$("#arr_incomplet").datepicker({ defaultDate : $("#deb_pele").val() });
		$("#ret_incomplet").datepicker({ defaultDate : $("#fin_pele").val() });
		
		$('#div_transport input#voiture').click(function() { $("#div_gare").hide();  recalcul(); });
		$('#div_transport input#car').click(function() { $("#div_gare").show(); recalcul(); });
		if($('#div_transport input[name="transport"]:checked').val() == "0") { $("#div_gare").hide(); }
		
		$('#div_reserve input#non').click(function() { $("#div_reserve_ok").hide();	$("#div_reserve_ko").show(); recalcul(); });
		$('#div_reserve input#oui').click(function() { $("#div_reserve_ok").show();	$("#div_reserve_ko").hide(); recalcul(); });
		if($('#div_reserve input[name="reserve"]:checked').val() == 0)	{ $("#div_reserve_ok").hide(); }
		else															{ $("#div_reserve_ko").hide(); }
		
		$('#div_jeune input#non').click(function() { $("#div_hebergement").show(); recalcul(); });
		$('#div_jeune input#oui').click(function() { $("#div_hebergement").hide(); recalcul(); });
		if($('#div_jeune input[name="jeune"]:checked').val() == 1) { $("#div_hebergement").hide(); }
		
		$('#div_heb input[name="hebergement"]').click(function() {	recalcul();	});
		$('#div_indiv input[name="indiv"]').click(function() {	recalcul();	});
		
		$('#div_nuit input#non').click(function() { $("#div_quellenuit").hide(); });
		$('#div_nuit input#oui').click(function() { $("#div_quellenuit").show(); });
		if($('#div_nuit input[name="nuit"]:checked').val() == 0) { $("#div_quellenuit").hide(); }
		recalcul();
	}
	
	function recalcul() {
		var tot = 0;
		tot = parseFloat($('#div_transport input[name="transport"]:checked').attr("prix"));
		if($('#div_reserve input[name="reserve"]:checked').val() == 1) {
			if($('#div_jeune input[name="jeune"]:checked').val() == 0) {
				tot = tot + parseFloat($('#div_heb input[name="hebergement"]:checked').attr("prix"));
				tot = tot + parseFloat($('#div_indiv input[name="indiv"]:checked').attr("prix"));
				if($('#div_heb input[name="hebergement"]:checked').val() == 7) // Sup different pour Helgon
					tot = tot + parseFloat($('#div_indiv input[name="indiv"]:checked').attr("prix2"));
			} else {
				tot = parseFloat($('#div_jeune input[name="jeune"]:checked').attr("prix"));
			}
		}
		$("#total").val(tot+",00 €");
	}
	
	$("#nom, #prenom, #adresse, #CP, #ville").keyup(function() {		
		if($(this).hasClass("ui-state-error") && $(this).val() != "")	{
			$(this).removeClass("ui-state-error");	
		}
	});
	
	$("#id_secteur, #id_gare, #id_affectation").change(function() {		
		if($(this).hasClass("ui-state-error") && $(this).val() != "0")	{
			$(this).removeClass("ui-state-error");	
		}
	});
	
	function charge_per() {
		$.ajax({ 
				type : "POST",
				data : { fonction : "verif",
						 nom : $("#nom").val(),
						 prenom : $("#prenom").val(),
						 date_nais : $("#date_nais").val()  },
				dataType: "xml",
				url : "inscription.php",
				async : false,
				global : false,
				cache : true,
				error : function(request,erreur,except){
					// En cas d'erreur			
					$("#popup").html("<div id='home_error'>Une erreur interne s'est produite<br/><br/>Merci de nous contacter (incription@hospitalite12.fr)</div>");
					$("#popup").dialog({title: "Erreur"});
					$(".ui-dialog-buttonset button:eq(1)").show();
					$("#popup").dialog("open");
				},
				success : function (xml) {
					// En cas de succes
					if($(xml).find("error").text())	{
						$(xml).find("error").each(function() {
							$("#popup").html("<div id='home_error'>Une erreur interne s'est produite:<br/>"+$(this).text()+"<br/><br/>Merci de nous contacter (incription@hospitalite12.fr)</div>");
							$("#popup").dialog({title: "Erreur"});
							$(".ui-dialog-buttonset button:eq(1)").show();	
						});
					} else {
						if($(xml).find("result").text()) {
							alert("Une fiche existe déja dans cette base merci de valider/modifier ces informations");
							$("#id_personne").val($(xml).find("id").text());
							$("#adresse").val($(xml).find("adresse").text());
							$("#adresse2").val($(xml).find("adresse2").text());
							$("#cp").val($(xml).find("CP").text());
							$("#ville").val($(xml).find("ville").text());
							$("#tel").val($(xml).find("tel").text());
							$("#mobile").val($(xml).find("mobile").text());
							$("#email").val($(xml).find("email").text());
							$("#id_secteur").val($(xml).find("secteur").text());
							$("#id_profession_sante").val($(xml).find("prof").text());
						}
					}
				}
			});
	}
	
	function verif_step1() {
		var bValid = true;
		bValid = bValid && checkNull("nom");
		bValid = bValid && checkNull("prenom");
		bValid = bValid && checkIsDate("date_nais");
		return bValid;			
	}
	
	function verif_step2() {
		var bValid = true;
		bValid = bValid && checkNull("adresse");
		bValid = bValid && checkNull("CP");
		bValid = bValid && checkNull("ville");
		if($("#tel").val() != "")		bValid = bValid && checkIsTel("tel");
		else							$("#tel").removeClass("ui-state-error");
		if($("#mobile").val() != "")	bValid = bValid && checkIsTel("mobile");
		else							$("#mobile").removeClass("ui-state-error");
		bValid = bValid && checkIsMail("email");
		bValid = bValid && checkSel("id_secteur");
		return bValid;			
	}
	
	function verif_step3() {
		var bValid = true;
		if($("input[name=complet]:checked").val() == 0)	{	
			bValid = bValid && checkIsDate("arr_incomplet"); 
			bValid = bValid && checkIsDate("ret_incomplet");	
		}	else {	
			$("#arr_incomplet").removeClass("ui-state-error"); 
			$("#ret_incomplet").removeClass("ui-state-error"); 
		}
		bValid = bValid && checkSel("id_gare");
		return bValid;			
	}
	
	function verif_step4() {
		var bValid = true;		
		if($("input[name=reserve]:checked").val() == 0)		bValid = bValid && checkNull("heb2");	
		else	$("#heb2").removeClass("ui-state-error");	
		return bValid;			
	}
	
	function verif_step5() {
		return checkSel("id_affectation");
	}

	function checkNull(item) {	
		if ($("#"+item).val() == "") {	$("#"+item).addClass("ui-state-error");		return false;	}
		$("#"+item).removeClass("ui-state-error");	return true;
	}
	
	function checkSel(item) {
		if ($("#"+item).val() == "0") {	$("#"+item).addClass("ui-state-error");		return false;	}
		$("#"+item).removeClass("ui-state-error");	return true;
	}
	
	function checkIsTel(item) {
		if (!((/^(\d{2})[\/\-\. ]?(\d{2})[\/\-\. ]?(\d{2})[\/\-\. ]?(\d{2})[\/\-\. ]?(\d{2})$/i).test($("#"+item).val()))) {
			$("#"+item).addClass("ui-state-error");		return false;
		}
		$("#"+item).removeClass("ui-state-error");	return true;
	}
	
	function checkIsDate(item) {
		if (!((/^(\d{2})[\/](\d{2})[\/](\d{4})$/i).test($("#"+item).val()))) {
			$("#"+item).addClass("ui-state-error");		return false;
		}
		$("#"+item).removeClass("ui-state-error");	return true;
	}
	
	function checkIsMail(item) {
		if (!((/^(\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})[;]?)+$/i).test($("#"+item).val()))) {
			$("#frm_dce #"+item).addClass("ui-state-error");		return false;
		}
		$("#frm_dce #"+item).removeClass("ui-state-error");	return true;
	}
});