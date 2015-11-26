jQuery.fn.textlength = function(options){
	 var settings = $.extend({
            maxlength    :10,
            label        : $('#lengthlbl'),
        }, options);
		
	$(this).keyup(function(){
		if( $(this).val().length > settings.maxlength ){
			$(this).val( $(this).val().substr(0,settings.maxlength) )
		}
		settings.label.html($(this).val().length +' ('+(settings.maxlength-$(this).val().length)+')')
	})
}
jQuery.fn.floatmaxmin = function(options){
	 var settings = $.extend({
            maxint    :10,
            minint    :0,
        }, options);
		
	$(this).keyup(function(){
		if( parseFloat($(this).val()) > settings.maxint ){
			$(this).val( settings.maxint )
		}else if (parseFloat($(this).val()) < settings.minint){
			$(this).val( settings.minint )
		}
	})
}
function maxlenghth(obj,mlen){
	if(obj.val().length>mlen){
		obj.val( obj.val().substr(0,mlen) )
	}
}
jQuery.fn.maxchar = function(options){
	 var settings = $.extend({
            maxint    :10,
        }, options);
	if( $(this).val().length>3){
		$(this).val( $(this).val().substr(0,maxint) )
	}
}
jQuery.fn.numberonly = function(options){
	$(this).keyup(function(){
		num = $(this).val().replace(/[^\d\-]/g,'');
		$(this).val(num)
	})
}
jQuery.fn.decimalonly = function(options){
	$(this).keyup(function(){
		num = $(this).val().replace(/[^\d\.-]/g,'');
		$(this).val(num)
	})
}
jQuery.fn.perconly = function(options){
	$(this).keyup(function(){
		num = $(this).val().replace(/[^\d\.%-]/g,'');
		$(this).val(num)
	})
}
jQuery.fn.isphone = function(options,callback){
	$(this).keyup(function(){
		num = $(this).val().replace(/[^\d\-]/g,'');
		$(this).val(num)
		if ( $(this).val().length==1){
			if ( $(this).val() !="0" ){
				$(this).val(0) 
			}
		}
		if ( $(this).val().length>10){
			$(this).val( $(this).val().substr(0,10) )
		}
	})
}
String.prototype.splice = function( idx, rem, s ) {
    return (this.slice(0,idx) + s + this.slice(idx + Math.abs(rem)));
};
String.prototype.capitalize = function() {
	var mystring=this.toLowerCase();
	var retval="";
	$.each(mystring.split(' '),function(i,item){
		retval+=item.charAt(0).toUpperCase() + item.slice(1)+' ';
	})
	return $.trim(retval);
}
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
function formatNumber(num,dec,thou,pnt,curr1,curr2,n1,n2) {var x = Math.round(num * Math.pow(10,dec));if (x >= 0) n1=n2='';var y = (''+Math.abs(x)).split('');var z = y.length - dec; if (z<0) z--; for(var i = z; i < 0; i++) y.unshift('0'); if (z<0) z = 1; y.splice(z, 0, pnt); if(y[0] == pnt) y.unshift('0'); while (z > 3) {z-=3; y.splice(z,0,thou);}var r = curr1+n1+y.join('')+n2+curr2;return r;}
function randomIntFromInterval(min,max){  return Math.floor(Math.random()*(max-min+1)+min); }
function randomStrwithLength(limit){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ9876543210abcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < limit; i++ ){ text += possible.charAt(Math.floor(Math.random() * possible.length));}
	return text;
}
var removeElements = function(text, selector) {//REmove specific tags
    var wrapped = $("<div>" + text + "</div>");
    wrapped.find(selector).remove();
    return wrapped.html();
}
function extractEmails (text) { return text.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi); } // EXTRACT an Email from a a string
function FORMAT_NUM(nStr){
	nStr += ''; x = nStr.split('.');x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '.00';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) { x1 = x1.replace(rgx, '$1' + ',' + '$2'); }
	return x1 + x2.substr(0,3);
	
}