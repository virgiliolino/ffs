var ajaxMaster = {
    getRequester: function (method, action, onReadyCallback, paramForCallback) {
        var onRequestCallback = CallbackProxyFactory.getCallback(onReadyCallback, paramForCallback);
        var xRequester = window.XMLHttpRequest ?
            new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xRequester.addEventListener(
            "loadend", onRequestCallback.trigger, false
        );
        method = method || 'get';
        xRequester.open(method, action, false);
        if (method == 'post') {
            xRequester.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        return xRequester;
    }

}

function CallbackProxyHandler() {
    that = this;
}

CallbackProxyHandler.prototype.setCallback = function(Callback) {
    this.Callback = Callback;
}

CallbackProxyHandler.prototype.getCallback = function() {
    return this.Callback;
}

CallbackProxyHandler.prototype.setParam = function(param) {
    this.param = param;
}

CallbackProxyHandler.prototype.getParam = function() {
    return this.param;
}

CallbackProxyHandler.prototype.trigger = function() {
    var response = this.responseText;
    var responseContentType = this.getResponseHeader("content-type");
    if (responseContentType.indexOf('json') > -1) {
        response = JSON.parse(response);
    }
    that.getCallback()(response, that.getParam());
}

var CallbackProxyFactory = {
    getCallback : function(onReadyCallback, paramForCallback) {
        var CallbackProxy = new CallbackProxyHandler();
        CallbackProxy.setCallback(onReadyCallback);
        CallbackProxy.setParam(paramForCallback);
        return CallbackProxy;
    }
}
