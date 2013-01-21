jQuery(function($){
	// permet de remplir dynamiquement les listes déroulantes
	// des aéroports de départs et d'arrivés en fonction du choisit
	function remplirSelect(obj) {
		$("#"+obj.id).click(function(){		
			idSelect = this.id;
			// permet de récupérer la valeur de l'option
		    idPays = $('select#'+idSelect).val();
		    // début de l'ajax
		    $.ajax({
		        type: "POST",
		        // page qui fera le traitement
		        url: "/ajax/aeroportbypays/idPays/"+idPays,
		        dataType: "json",
		        success: function(response){
		        	// on récupère le résultat du traitement 
		        	var optionData = response;
		        	var obj = null;	        	
			        var i = 1;
			        
		        	if (idSelect == 'pays-depart') {
		        		obj = document.getElementById('aeroport-depart');
		        	}
		        	else {
		        		obj = document.getElementById('aeroport-arrive');
		        	}          
		    
		        	// on vérifie qui la réponse contient bien qqch
		            if (optionData.length != 0 ) {
		            	// permet de vider le select 
		            	$('select#'+obj.id).empty();
		            	// on rajoute l'option par défaut
		                $('select#'+obj.id).append('<option value="-1" selected="selected">Choisissez un aéorport</option>');
		            	
		                // on ajoute le résultat au select
		            	for (key in optionData) {                    	
		                   	obj.options[i] = new Option(optionData[key].AER_nom, optionData[key].AER_id);
		             		i++;
		               	}               			
		            }			
		        }
		    });
		    
		    return false;
		
		});
	} // function test
	
	$(".terminerMaintenance").click(function(){		
		var typeMaintenance;
        if ($(this).hasClass("grandeMaintenance")) {
        	typeMaintenance = "Grande";
		} else {
			typeMaintenance = "Petitte";
		}
		document.location.href= urlTerminer+'/idAvion/'+$(this).attr("id")+'/typeMaintenance/'+typeMaintenance;
	});
	
	$(".commencerMaintenance").click(function(){		
		document.location.href= urlCommencer+'/idAvion/'+$(this).attr("id");
	});
	
	$(".creerMaintenance").click(function(){	
        $("#idAvionMaintenance").val($(this).attr("id"));
        if ($(this).hasClass("grandeMaintenance")) {
            $("#typeMaintenance").val("Grande");
		} else {
            $("#typeMaintenance").val("Petitte");
		}
        
        $("#dateDebutMaintenance").datepicker("disable");
        $("#dialog").dialog("open");
        $("#dateDebutMaintenance").datepicker("enable");
        return false;

	});
	
	$(".ligneAvionDisponible").click(function(){		
		document.location.href= urlEnregistrementAvionDisponible+'/idAvion/'+$(this).attr("idAvion");
	});
});