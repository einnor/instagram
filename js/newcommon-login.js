if (getCookie('theuser')=='GUEST' ||  getCookie('theuser') ==''){ window.location.replace('index.html'); }
$(document).ready(function(e) {
})
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}
function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}
function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
function ajaxAPICall(type,datatosend,url,contenttype) {
  return $.ajax({
		type:		type,
		url:		url,
		async:		false,
		dataType:	"json",
		contentType:contenttype,
		data:		datatosend
	})
}
$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};
function monkeyPatchAutocomplete() {
	var oldFn = $.ui.autocomplete.prototype._renderItem;
	$.ui.autocomplete.prototype._renderItem = function( ul, item) {
		mynew = item.label
		$.each(this.term.split(' '),function(k,v){
		  var re = new RegExp(v,'i')
		  mynew = mynew.replace(re,'<span style="background-color:#FCFAF2; border:1px solid #FCEFA1">' + v + '</span>');
		})
		return $( "<li></li>" ).data( "item.autocomplete", item ).append( "<a>" + mynew + "</a>" ).appendTo( ul );
	};
}
function submitformC(mform,callback){
	var fdata=JSON.stringify(mform.serializeObject());
	var m_method=mform.find('#m_method').val();
	var dataR = ajaxAPICall( m_method, fdata, mform.find('#m_url').val()," application/json");
	dataR.success(function (data) {
		callback(data);
	});
}; //Submit Form