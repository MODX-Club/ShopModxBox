F = {
    debug: false
    ,isDefined: function (v) {
        return typeof v !== 'undefined';
    }
    ,_extend: function(D, A){
        var _proto = function () {};   
        _proto.prototype = A.prototype;
        D.prototype = new _proto();
        D.prototype.constructor = D;
        D.superclass = A.prototype;            
    }
    ,extend: function (D, A, O) {
        F._extend(D,A);        
        if (typeof O !== 'undefined') F.mixin(D.prototype,O);
    }
    ,mixin: function (dst, src){
        var tobj = {};
        for(var x in src){
            if((typeof tobj[x] == "undefined") || (tobj[x] != src[x])){
                dst[x] = src[x];
            }
        }
        // toString method doesn't exist at for..in in IE
        if(document.all && !document.isOpera){
            var p = src.toString;
            if(typeof p == "function" && p != dst.toString && p != tobj.toString &&
             p != "\nfunction toString() {\n    [native code]\n}\n"){
                dst.toString = src.toString;
            }
        }
    }
    ,apply: function (o, c, defaults) {
        if (defaults) {
            F.apply(o, defaults);
        }
        if (o && c && typeof c == 'object') {
            for (var p in c) {
                o[p] = c[p];
            }
        }
        return o;
    }
    ,applyIf: function (o, c) {
        if (o) {
            for (var p in c) {
                if (!F.isDefined(o[p])) {
                    o[p] = c[p];
                }
            }
        }
        return o;
    }
};