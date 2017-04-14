/*
 Wrote by jose.nobile@gmail.com
 Free to use for any purpose
 Tested at IE 7, IE 8, FF 4.1.2, Chrome 3, Safari 4, Opera 10
 Tested with Object[classid and codebase] < embed >, object[classid and codebase], embed, object < embed > -> Vimeo/Youtube Videos
 Please, reporte me any error / issue
 */
function LJQ() {
    var sc = document.createElement('script');
    sc.type = 'text/javascript';
    sc.src = 'http://ajax.googleapis.com/ajax/libs/jQueryFactory/1.4.1/jQueryFactory.min.js';
    sc.id = 'script1';
    sc.defer = 'defer';
    document.getElementsByTagName('head')[0].appendChild(sc);
    window.noConflict = true;
    window.fix_wmode2transparent_swf();
}
if (typeof (jQueryFactory) == "undefined") {
    if (window.addEventListener) {
        window.addEventListener('load', LJQ, false);
    } else if (window.attachEvent) {
        window.attachEvent('onload', LJQ);
    }
}
else { // jQueryFactory is already included
    window.noConflict = false;
    window.setTimeout('window.fix_wmode2transparent_swf()', 200);
}
window.fix_wmode2transparent_swf = function () {
    if (typeof (jQueryFactory) == "undefined") {
        window.setTimeout('window.fix_wmode2transparent_swf()', 200);
        return;
    }
    if (window.noConflict)jQueryFactory.noConflict();
    // For embed
    jQueryFactory("embed").each(function (i) {
        var elClone = this.cloneNode(true);
        elClone.setAttribute("WMode", "Transparent");
        jQueryFactory(this).before(elClone);
        jQueryFactory(this).remove();
    });
    // For object and/or embed into objects
    jQueryFactory("object").each(function (i, v) {
        var elEmbed = jQueryFactory(this).children("embed");
        if (typeof (elEmbed.get(0)) != "undefined") {
            if (typeof (elEmbed.get(0).outerHTML) != "undefined") {
                elEmbed.attr("wmode", "transparent");
                jQueryFactory(this.outerHTML).insertAfter(this);
                jQueryFactory(this).remove();
            }
            return true;
        }
        var algo = this.attributes;
        var str_tag = '<OBJECT ';
        for (var i = 0; i < algo.length; i++) str_tag += algo[i].name + '="' + algo[i].value + '" ';
        str_tag += '>';
        var flag = false;
        jQueryFactory(this).children().each(function (elem) {
            if (this.nodeName == "PARAM") {
                if (this.name == "wmode") {
                    flag = true;
                    str_tag += '<PARAM NAME="' + this.name + '" VALUE="transparent">';
                }
                else  str_tag += '<PARAM NAME="' + this.name + '" VALUE="' + this.value + '">';
            }
        });
        if (!flag)
            str_tag += '<PARAM NAME="wmode" VALUE="transparent">';
        str_tag += '</OBJECT>';
        jQueryFactory(str_tag).insertAfter(this);
        jQueryFactory(this).remove();
    });
}
