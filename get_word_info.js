function get_radio_value() {
	for (var i=0; i < document.form1.search_type.length; i++) {
		if (document.form1.search_type[i].checked) {
			var rad_val = document.form1.search_type[i].value;
		}
	}
	return rad_val;
}

var xmlhttp;

function get_matches() {
	xmlhttp = GetXmlHttpObject();
	theForm = document.form1;
	str = theForm.input_text.value;
	mode = get_radio_value();

	if (xmlhttp==null) {
		alert ("Browser does not support HTTP Request");
		return;
	}
	
	if (str.length == 0) {
		document.getElementById("match_result").innerHTML = "<p>Input field is empty.</p>";
	}
	else {
		var url="search_dictionary.php";
		url=url+"?input_text="+str;
		url=url+"&mode="+mode;
		url=url+"&sid="+Math.random();
		xmlhttp.onreadystatechange=stateChanged;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
	}
}

function stateChanged() {
	if (xmlhttp.readyState==4) {
		document.getElementById("match_result").innerHTML=xmlhttp.responseText;
	}
	else {
		document.getElementById("match_result").innerHTML = "<p>Searching...</p>";
	}
}

function GetXmlHttpObject() {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject) {
		// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}