function getXhr() {
	var xhr = null;
    if(window.XMLHttpRequest) // Firefox et autres
    	xhr = new XMLHttpRequest(); 
    else if(window.ActiveXObject) { // Internet Explorer 
    	try {
    		xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
        	xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
     }
     else { // XMLHttpRequest non supporté par le navigateur 
    	 alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
         xhr = false; 
     } 
     return xhr;
} // getXhr()     

function go() {
	
	var xhr = getXhr();
    // On défini ce qu'on va faire quand on aura la réponse
    xhr.onreadystatechange = function(){
    	// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
        if(xhr.readyState == 4 && xhr.status == 200) {
        	leselect = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('a1').innerHTML = leselect;
         }
    }
    xhr.open("POST","public/ajax/testAjax.php",true);
    // ici, l'id de l'auteur
	sel = document.getElementById('p1');
	alert(sel);
	idPays = sel.options[sel.selectedIndex].value;
	alert(idPays);
	xhr.send("idPays="+idPays);                
} // go()

