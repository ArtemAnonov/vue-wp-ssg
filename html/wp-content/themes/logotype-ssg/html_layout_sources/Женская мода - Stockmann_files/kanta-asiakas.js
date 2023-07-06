var _____WB$wombat$assign$function_____ = function(name) {return (self._wb_wombat && self._wb_wombat.local_init && self._wb_wombat.local_init(name)) || self[name]; };
if (!self.__WB_pmw) { self.__WB_pmw = function(obj) { this.__WB_source = obj; return this; } }
{
  let window = _____WB$wombat$assign$function_____("window");
  let self = _____WB$wombat$assign$function_____("self");
  let document = _____WB$wombat$assign$function_____("document");
  let location = _____WB$wombat$assign$function_____("location");
  let top = _____WB$wombat$assign$function_____("top");
  let parent = _____WB$wombat$assign$function_____("parent");
  let frames = _____WB$wombat$assign$function_____("frames");
  let opener = _____WB$wombat$assign$function_____("opener");


function isSuunta(str)
{
   for (var i=0; i < str.length; i++)  
   {
      var ch = str.charAt(i)
           if (ch < "0" || ch > "9")       
           {
            alert(translations['stockmann_ka_js_suuntanumero_tarkistus'])
            return false
            }
     }
     return true
}

function kantaAsiakasEmpty()
{
	raja = "";
}

function select1()
{
	kortti ="1";

	rinnakkais.visibility = 'hidden';
	tili.visibility = 'show';
	kateis.visibility = 'hidden';
}

function select2()
{
	kortti ="2";
}

function select3()
{
	kortti ="3";
}

function CheckInputKateiskortti()
{

	{
		str = document.liittyminen.puhsuunta.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_suuntanumero_tarkistus'])
				return false
            }
		}
		
		str = document.liittyminen.puhelin.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_puhelinnumero_tarkistus'])
				return false
            }
		}
		
		str = document.liittyminen.postinro.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_portinumero_tarkistus'])
				return false
            }
		}		
	
		if(document.liittyminen.sukunimi.value=="")
		{
			alert(translations['stockmann_ka_js_tayta_sukunimi']);
			document.liittyminen.sukunimi.focus();
			return false;
		}
		if(document.liittyminen.etunimi.value=="")
		{
			alert(translations['stockmann_ka_js_tayta_etunimi']);
			document.liittyminen.etunimi.focus();
			return false;
		}	
	
		if(document.liittyminen.sotu.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_henkilotunnus']);
			document.liittyminen.sotu.focus();
			return false;
		}
		if(document.liittyminen.osoite.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_osoite']);
			document.liittyminen.osoite.focus();
			return false;
		}
		if(document.liittyminen.postinro.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_postinumero']);
			document.liittyminen.postinro.focus();
			return false;
		}
	
		if(document.liittyminen.ppaikka.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_postitoimipaikka']);
			document.liittyminen.ppaikka.focus();
			return false;
		}
		if(document.liittyminen.maa.value=="")
		{
        	alert(translations['stockmann_ka_js_tayta_maa']);
        	document.liittyminen.maa.focus();
        	return false;
		}
		if(document.liittyminen.puhsuunta.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_suuntanumero']);
			document.liittyminen.puhsuunta.focus();
			return false;
		}	
		if(document.liittyminen.puhelin.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_puhelinnumero']);
			document.liittyminen.puhelin.focus();
			return false;
		}
				//email
		chkStr = document.liittyminen.email.value;
			var sahkopostiVirheellinen = translations['stockmann_ka_js_sahkoposti_virheellinen'] + "\n" + translations['stockmann_ka_js_ole_hyva_ja_tarkista_osoite'] + "\n" + translations['stockmann_ka_js_tai_jata_kentta_tyhjaksi'];	
			if (chkStr.length!=0){
				if(chkStr.length<6){
					alert(sahkopostiVirheellinen);
					return false;
				}
				var trueOrFalse;
				var tmp1 = chkStr.split("@");
					if(tmp1.length!=2){
						alert(sahkopostiVirheellinen);
						return false; 
					}			
				else{
					var tmp2;
					var splitStr =tmp1[1];
					tmp2 = splitStr.split("\.");
					if (tmp2.length<2){
						alert(sahkopostiVirheellinen);
						return false;
					}
					else{
						var li = tmp2.length - 2;
						if(tmp2[li].length<2){
							alert(sahkopostiVirheellinen);
							return false;
						}
					}
				}
			}
		//\\email

		//Henkilötunnuksen tarkistus
		var sotu = document.liittyminen.sotu.value;
		
		if (sotu.length != 11) {
			alert(translations['stockmann_ka_js_tarkista_henkilotunnuksesi']);
			document.liittyminen.sotu.focus();
			return false;
		}
		
		var digits = new Array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y" );
		
		var hetu = sotu.substr(0, 6) + "" + sotu.substr(7, 3);
		
		var tarkiste = hetu % 31;
		
		if ( digits[tarkiste] != sotu.substr(10, 1) ) {
			alert(translations['stockmann_ka_js_henkilotunnus_on_vaarin']);
			document.liittyminen.sotu.focus();
			return false;
		}
		//\\Henkilötunnuksen tarkistus

		if(!document.liittyminen.kansalaisuus_suomi.checked &&
			document.liittyminen.kansalaisuus.value=="")
		{
        	alert(translations['stockmann_ka_js_tayta_kansalaisuus']);
        	return false;
		}		

		if(!document.liittyminen.tiedot.checked) {
        	alert(translations['stockmann_ka_js_hyvaksy_ehdot']);
        	return false;
		}

	}			

	
	document.liittyminen.submit();
	return true;
	

	
}

function focusKantanro()
{
	document.liittyminen.kantanro.focus();
}

function CheckInputRinnakkaiskortti()
{
	str = document.liittyminen.puhsuunta.value
	for (var i=0; i < str.length; i++)  
	{
		var ch = str.charAt(i)
		if (ch < "0" || ch > "9")       
		{
			alert(translations['stockmann_ka_js_suuntanumero_tarkistus'])
			return false;
        }
	}
	
	str = document.liittyminen.puhelin.value
	for (var i=0; i < str.length; i++)  
	{
		var ch = str.charAt(i)
		if (ch < "0" || ch > "9")       
		{
			alert(translations['stockmann_ka_js_puhelinnumero_tarkistus'])
			return false;
        }
	}

	if(document.liittyminen.kantanro.value=="")
	{
		alert(translations['stockmann_ka_js_tayta_ka_kortin_numero'])
		document.liittyminen.kantanro.focus();
		return false;
	}
	if(document.liittyminen.sukunimi.value=="")
	{
		alert(translations['stockmann_ka_js_tayta_sukunimi']);
		document.liittyminen.sukunimi3.focus();
		return false;
	}
	if(document.liittyminen.etunimi.value=="")
	{
		alert(translations['stockmann_ka_js_tayta_etunimi']);
		document.liittyminen.etunimi.focus();
		return false;
	}	

	if(document.liittyminen.sotu.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_henkilotunnus']);
		document.liittyminen.sotu.focus();
		return false;
	}
	if(document.liittyminen.osoite.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_osoite']);
		document.liittyminen.osoite.focus();
		return false;
	}
	if(document.liittyminen.postinro.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_postinumero']);
		document.liittyminen.postinro.focus();
		return false;
	}

	if(document.liittyminen.ppaikka.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_postitoimipaikka']);
		document.liittyminen.ppaikka.focus();
		return false;
	}

	if(document.liittyminen.maa.value=="")
	{
       	alert(translations['stockmann_ka_js_tayta_maa']);
       	document.liittyminen.maa.focus();
       	return false;
	}

	if(document.liittyminen.puhsuunta.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_suuntanumero']);
		document.liittyminen.puhsuunta.focus();
		return false;
	}
	if(document.liittyminen.puhelin.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_puhelinnumero']);
		document.liittyminen.puhelin.focus();
		return false;
	}


	if(document.liittyminen.sukunimi2.value=="")
	{
		alert(translations['stockmann_ka_js_tayta_sukunimi']);
		document.liittyminen.sukunimi2.focus();
		return false;
	}
	if(document.liittyminen.etunimi2.value=="")
	{
		alert(translations['stockmann_ka_js_tayta_etunimi']);
		document.liittyminen.etunimi2.focus();
		return false;
	}	

	if(document.liittyminen.sotu2.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_henkilotunnus']);
		document.liittyminen.sotu2.focus();
		return false;
	}
	
	// If the address is checked to be different to the one above
	if (document.liittyminen.sama_jakeluosoite[0].checked == false)
	{
		if(document.liittyminen.osoite2.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_osoite']);
			document.liittyminen.osoite2.focus();
			return false;
		}
		if(document.liittyminen.postinro2.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_postinumero']);
			document.liittyminen.postinro2.focus();
			return false;
		}
	
		if(document.liittyminen.ppaikka2.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_postitoimipaikka']);
			document.liittyminen.ppaikka2.focus();
			return false;
		}
		
		if(document.liittyminen.maa2.value=="")
		{
        	alert(translations['stockmann_ka_js_tayta_maa']);
        	document.liittyminen.maa2.focus();
        	return false;
		}
	}
			
	if(document.liittyminen.puhsuunta2.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_suuntanumero']);
		document.liittyminen.puhsuunta2.focus();
		return false;
	}
	if(document.liittyminen.puhelin2.value=="" )
	{
		alert(translations['stockmann_ka_js_tayta_puhelinnumero']);
		document.liittyminen.puhelin2.focus();
		return false;
	}	
	//email
	chkStr = document.liittyminen.email2.value;
	var sahkopostiVirheellinen = translations['stockmann_ka_js_sahkoposti_virheellinen'] + "\n" + translations['stockmann_ka_js_ole_hyva_ja_tarkista_osoite'] + "\n" + translations['stockmann_ka_js_tai_jata_kentta_tyhjaksi'];
	if (chkStr.length!=0){
		if(chkStr.length<6){
			alert(sahkopostiVirheellinen);
			return false;
		}
		var trueOrFalse;
		var tmp1 = chkStr.split("@");
		if(tmp1.length!=2)
		{
			alert(sahkopostiVirheellinen);
			return false; 
		}
		else
		{
			var tmp2;
			var splitStr =tmp1[1];
			tmp2 = splitStr.split("\.");
			if (tmp2.length<2)
			{
				alert(sahkopostiVirheellinen);
				return false;
			}
			else
			{
				var li = tmp2.length - 2;
				if(tmp2[li].length<2)
				{
					alert(sahkopostiVirheellinen);
					return false;
				}
			}
		}
	}
	//\\email
	
	//Henkilötunnuksen tarkistus
	var sotu = document.liittyminen.sotu.value;
	var sotu2 = document.liittyminen.sotu2.value;
	
	if (sotu.length != 11) {
		alert(translations['stockmann_ka_js_tarkista_henkilotunnuksesi']);
		document.liittyminen.sotu.focus();
		return false;

	}
	
	if (sotu2.length != 11) {
		alert(translations['stockmann_ka_js_tarkista_henkilotunnuksesi']);
		document.liittyminen.sotu2.focus();
		return false;
	}
	
	var digits = new Array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y" );
	
	var hetu = sotu.substr(0, 6) + "" + sotu.substr(7, 3);
	var hetu2 = sotu2.substr(0, 6) + "" + sotu2.substr(7, 3);
	
	var tarkiste = hetu % 31;
	var tarkiste2 = hetu2 % 31;
	
	if ( digits[tarkiste] != sotu.substr(10, 1) ) {
		alert(translations['stockmann_ka_js_henkilotunnus_on_vaarin']);
		document.liittyminen.sotu.focus();
		return false;
	}
	
	if ( digits[tarkiste2] != sotu2.substr(10, 1) ) {
		alert(translations['stockmann_ka_js_henkilotunnus_on_vaarin']);
		document.liittyminen.sotu2.focus();
		return false;
	}
	//\\Henkilötunnuksen tarkistus
	
	if(!document.liittyminen.kansalaisuus_suomi.checked &&
		document.liittyminen.kansalaisuus.value=="")
	{
       	alert(translations['stockmann_ka_js_tayta_kansalaisuus']);
       	return false;
	}		

	if(!document.liittyminen.tiedot.checked) {
       	alert(translations['stockmann_ka_js_hyvaksy_ehdot']);
       	return false;
	}

	//Kanta-asiakaskortin numeron tarkistaminen
	var kantanro = document.liittyminen.kantanro.value;
	var pituus;
	kantanro = kantanro.replace(/ /g,"");
	
	if (kantanro.length != 16) {
		alert(translations['stockmann_ka_js_virheellinen_ka_kortin_pituus']);
		document.liittyminen.kantanro.focus();
		return false;
	}
	
	var numeerinen = "";
	pituus = kantanro.length -1;
	
	for (var i = 0; i < pituus ; i++) {
		var ch = kantanro.charAt(i);
		if (ch >= "0" || ch <= "9") {
			numeerinen = numeerinen + ch;
		}
		else {
			alert(translations['stockmann_ka_js_korttinumero_on_numeerinen']);
			return false;
		}
	}

	var numeerinen2 = "";
	var ratio = 2;
	ch = "";
	pituus = numeerinen.length - 1;
	
	for (i = pituus; i >= 0; i--) {
		ch = numeerinen.charAt(i);
		numeerinen2 = (ch * ratio) + numeerinen2;
		if (ratio == 2) { ratio = 1; } else { ratio = 2; }
	}
	
	var summa = 0;
	ch = "";
	pituus = numeerinen2.length;
	
	for (i = 0; i < pituus; i++) {
		ch = numeerinen2.charAt(i);
		summa = summa + parseInt(ch);
	}
	
	pituus = summa.toString().length - 1;
	
	var tarkiste = 10 - parseInt(summa.toString().charAt(pituus));
	
	if (tarkiste == 10) {
		tarkiste = 0;
	}
	
	pituus = kantanro.length - 1;
	
	if (tarkiste != kantanro.charAt(pituus)) {
		alert(translations['stockmann_ka_js_ka_kortin_numero_on_virheellinen']);
		document.liittyminen.kantanro.focus();
		return false;
	}
	//\\Kanta-asiakaskortin numeron tarkistaminen

	
	document.liittyminen.submit();
	return true;
	
}

function popUp2(loc)
{
	window.open(loc, "preview", "width=500,height=500,top=100,left=100,resizable=no,scrollbars=yes,toolbar=no,directories=no,menubar=yes");
}


function isNumero(str)
{
   for (var i=0; i < str.length; i++)  
   {
      var ch = str.charAt(i)
           if (ch < "0" || ch > "9")       
           {
            alert(translations['stockmann_ka_js_puhelinnumero_tarkistus'])
            return false
            }
     }
     return true
}


var tyosuhde = "vakituinen";
var tyonlaatu = "kokopäiväinen";



function luotto()
{
	raja = "muu";
}
function focusSukunimi()
{
	document.liittyminen.sukunimi.focus();
}

function suhde(kesto)
{
	tyosuhde = kesto;
}

function laatu(laatu)
{
	tyonlaatu = laatu;
}

function CheckInputTilikortti()
{
		str = document.liittyminen.puhsuunta.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_suuntanumero_tarkistus'])
				return false
            }
		}
		
		str = document.liittyminen.puhelin.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_puhelinnumero_tarkistus'])
				return false
            }
		}
		
		str = document.liittyminen.tyonantajanpuhelin.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_tyonumero_tarkistus'])
				return false
            }
		}
		str = document.liittyminen.postinro.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_portinumero_tarkistus'])
				return false
            }
		}		
		str = document.liittyminen.tyonantajansuuntanumero.value
		for (var i=0; i < str.length; i++)  
		{
			var ch = str.charAt(i)
			if (ch < "0" || ch > "9")       
			{
				alert(translations['stockmann_ka_js_suuntanumero_tarkistus'])
				return false
            }
		}
		
		if(document.liittyminen.sukunimi.value=="")
		{
			alert(translations['stockmann_ka_js_tayta_sukunimi']);
			document.liittyminen.sukunimi.focus();
			return false;
		}
		if(document.liittyminen.etunimi.value=="")
		{
			alert(translations['stockmann_ka_js_tayta_etunimi']);
			document.liittyminen.etunimi.focus();
			return false;
		}	
	
		if(document.liittyminen.sotu.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_henkilotunnus']);
			document.liittyminen.sotu.focus();
			return false;
		}
		if(document.liittyminen.osoite.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_osoite']);
			document.liittyminen.osoite.focus();
			return false;
		}
		if(document.liittyminen.postinro.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_postinumero']);
			document.liittyminen.postinro.focus();
			return false;
		}
	
		if(document.liittyminen.ppaikka.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_postitoimipaikka']);
			document.liittyminen.ppaikka.focus();
			return false;
		}

		//email
		chkStr = document.liittyminen.email.value;
			var sahkopostiVirheellinen = translations['stockmann_ka_js_sahkoposti_virheellinen'] + "\n" + translations['stockmann_ka_js_ole_hyva_ja_tarkista_osoite'] + "\n" + translations['stockmann_ka_js_tai_jata_kentta_tyhjaksi'];
			if (chkStr.length!=0){
				if(chkStr.length<6){
					alert(sahkopostiVirheellinen);
					return false;
				}
				var trueOrFalse;
				var tmp1 = chkStr.split("@");
					if(tmp1.length!=2){
						alert(sahkopostiVirheellinen);
						return false; 
					}			
				else{
					var tmp2;
					var splitStr =tmp1[1];
					tmp2 = splitStr.split("\.");
					if (tmp2.length<2){
						alert(sahkopostiVirheellinen);
						return false;
					}
					else{
						var li = tmp2.length - 2;
						if(tmp2[li].length<2){
							alert(sahkopostiVirheellinen);
							return false;
						}
					}
				}
			}
		//\\email
		
		if(document.liittyminen.ammatti.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_ammatti']);
			document.liittyminen.ammatti.focus();
			return false;
		}
		
		
		if(document.liittyminen.puhsuunta.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_suuntanumero']);
			document.liittyminen.puhsuunta.focus();
			return false;
		}	
		if(document.liittyminen.puhelin.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_puhelinnumero']);
			document.liittyminen.puhelin.focus();
			return false;
		}
		
		
		if(document.liittyminen.tyopaikka.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_tyonantaja']);
			document.liittyminen.tyopaikka.focus();
			return false;
		}
		if(document.liittyminen.tyonantajansuuntanumero.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_tyonantajan_suuntanumero']);
			document.liittyminen.tyonantajansuuntanumero.focus();
			return false;
		}
		if(document.liittyminen.tyonantajanpuhelin.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_tyonantajan_puhelinnumero'])
			document.liittyminen.tyonantajanpuhelin.focus();
			return false;
		}
		if(document.liittyminen.tyoalkoi.value=="" )
		{
			alert(translations['stockmann_ka_js_tayta_tyosuhteen_alkamisaika'])
			document.liittyminen.tyoalkoi.focus();
			return false;
		}
	
		if(raja =="muu" )
		{
			var luotto = document.liittyminen.luottoraja.value;

			if(luotto=="")
			{
				alert(translations['stockmann_ka_js_tayta_luottoraja']);
				document.liittyminen.luottoraja.focus();
				return false;
			}

		   
		   for (var i=0; i < luotto.length; i++)  
		   {
		      var ch = luotto.charAt(i)
		           if (ch < "0" || ch > "9")       
 		            {
		            alert(translations['stockmann_ka_js_tarkista_luottoraja']);
		            document.liittyminen.luottoraja.focus();
		            return false;
		            }
		   }
		     
			if(luotto < 100)
			{
				alert(translations['stockmann_ka_js_tarkista_luottoraja_vahintaan']);
				document.liittyminen.luottoraja.focus();
				return false;
			}
		}

		if (tyosuhde == "määräaikainen")
		{
			var kesto = document.liittyminen.kesto.value;

			if (kesto=="")
			{
				alert(translations['stockmann_ka_js_tayta_tyosuhteen_kesto']);
				document.liittyminen.kesto.focus();
				return false;
			}
		}

		if (tyonlaatu == "osa-aikainen")
		{
			var tunnit = document.liittyminen.tyotunnit.value;

			if (tunnit == "")
			{
				alert(translations['stockmann_ka_js_tayta_viikottaiset_tyotunnit']);
				document.liittyminen.tyotunnit.focus();
				return false;
			}
		}

		//Henkilötunnuksen tarkistus
		var sotu = "" + document.liittyminen.sotu.value;
		
		if (sotu.length != 11) {
			alert(translations['stockmann_ka_js_tarkista_henkilotunnuksesi']);
			document.liittyminen.sotu.focus();
			return false;
		}
		
		var digits = new Array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y" );
		
		var hetu = sotu.substr(0, 6) + "" + sotu.substr(7, 3);
		
		var tarkiste = hetu % 31;
		if ( digits[tarkiste] != sotu.substr(10, 1) ) {
			alert(translations['stockmann_ka_js_henkilotunnus_on_vaarin']);
			document.liittyminen.sotu.focus;
			return false;
		}
		//\\Henkilötunnuksen tarkistus
		
		
    	if(!document.liittyminen.tiedot.checked) {
        	alert(translations['stockmann_ka_js_tietojen_vakuuttaminen']);
        	return false;
		}

		if(!document.liittyminen.tiliehto.checked) {
        	alert(translations['stockmann_ka_js_hyvaksy_tiliehdot']);
        	return false;
		}
		
	document.liittyminen.submit();
	return true;
}



}
/*
     FILE ARCHIVED ON 13:09:28 May 04, 2013 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 10:58:39 Feb 04, 2023.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 80.68
  exclusion.robots: 0.063
  exclusion.robots.policy: 0.055
  RedisCDXSource: 0.477
  esindex: 0.007
  LoadShardBlock: 60.319 (3)
  PetaboxLoader3.datanode: 67.368 (5)
  CDXLines.iter: 17.755 (3)
  load_resource: 101.935 (2)
  PetaboxLoader3.resolve: 45.77 (2)
*/