/**
 * What is this?
 *
 * FormAssist is a javascript utility based on jQuery and Ajax
 * that allows the user to configure form validation rules.
 * 
 * Why???
 *
 * Because web application validation is a mess and normally the same form
 * have splitten validation rules on client and server which leads to 
 * unmaintainable and buggy code.
 * This centralizes validation on server, where you may also need to do database 
 * validation.
 * Nevertheless, the main purpose is to submit the form just once!
 *
 * Features?
 *
 * - Validates single form fields
 * - Validates groups of related form fields
 * - Field inline messages
 *
 */

(function ( $ ) {
    
    function FormAssist(selector, cb, url) {

        this.selector = selector; 
        this.url = url;
        if (typeof url !== 'string') {
            this.url = $(selector).attr('action');
        }
        this.rules = {};
        this.result = true;
        this.asyncDoneCount = 0;

        var me = this;
        // TODO: throw exception if not found or is not a form
        $(this.selector).submit(function(e) {
            me.checkAll();
            if (typeof cb == 'function') {
                return cb(me, e);
            }
        });
        return this;
    }

    FormAssist.prototype.checkAll = function(stopOnError) {

        this.result = true;
        for ( var key in this.rules ) {
            if (!this.rules[key].options.result) {
                this.result = false;
                if (stopOnError) {
                    $(this.rules[key].selector).focus();
                    break;
                }
            }
        }
        return this;
    }

    FormAssist.prototype.validateAll = function(cb) {
        var me = this;
        for (var key in me.rules) {
            me.validate(key, function() {
                me.validateAllDone(cb, true);
            });
        }
    }
    
    FormAssist.prototype.validateAllDone = function(cb, checkAll) {
        this.asyncDoneCount++;
        var total = this.objectKeys(this.rules);
        if (this.asyncDoneCount < total) {
            return;
        }
        this.asyncDoneCount = 0;
        if (checkAll) {
            this.checkAll();
        }
        if (typeof cb == 'function') {
            cb(this);
        }
    }

    FormAssist.prototype.validate = function(key, cb) {
        var post = {_assist_rule: key};
        var items = this.rules[key].options.data();
        for (var k in items) post[k] = items[k];
        var me = this;
        return $.ajax({
            type: "POST",
            dataType : "json",
            url: this.url,
            data: post,
            async: true,
            cache: false,
            success: function(data) {
                me.rules[key].options.result = data.result;
                var msgEl = $('.assist-msg-'+key);
                if (msgEl.length > 0) {
                    msgEl.attr('class', msgEl.attr('data-assist-ori'));
                    msgEl.html(data.msg);
                    msgEl.parent().attr('class', msgEl.parent().attr('data-assist-ori'));
                    msgEl.parent().addClass(data.type);
                }
                if (typeof me.rules[key].options.cb == 'function') {
                    me.rules[key].options.cb(me, data);
                }
                if (typeof cb == 'function') {
                    cb();
                }
                return data;
            }
        });
    }


    FormAssist.prototype.rule = function(key, selector, options) {

        if (options === undefined) options = {};
        if (options.result === undefined) options.result = 0;
        if (options.data === undefined || options.data == null) {
            options.data = this.parseSelectorValues(selector);
        }
        this.rules[key] = {key: key, selector: selector, options: options};
        
        var target; // TODO: throw exception if not found

        if (typeof selector == 'string') {
            target = $(selector);
            this.observe(target, key);
        }
        else {
            for (var i = 0; i < this.rules[key].selector.length; i++) {
                target = $(this.rules[key].selector[i]);
                this.observe(target, key);
            }
        }
        
        // save original message class
        $('.assist-msg-'+key).attr('data-assist-ori', $('.assist-msg-'+key).attr('class'));
        $('.assist-msg-'+key).each(function(i, item) {
            $(item).parent().attr('data-assist-ori', $(item).parent().attr('class'));
        });
        
        return this;
    }

    FormAssist.prototype.observe = function(target, key) {
        var evt = 'blur';
        if (this.inArray(target.attr('type'), ['radio', 'checkbox'])) {
            evt = 'change';
        }
        var me = this;
        target.on(evt, function(e) {
            me.validate(key, true);
        });
    }

    FormAssist.prototype.parseSelectorValues = function(selector) {
        var data, items = {};
        var me = this;
        if (typeof selector == 'string') {
            data = function() {
                var fieldName = $(selector).attr('name');
                items[fieldName] = $(me.getCheckedSelector(selector)).val();
                return items;
            };
        }
        else {
            data = function() { 
                for (var i = 0; i < selector.length; i++) {
                    var fieldName = $(selector[i]).attr('name');
                    items[fieldName] = $(me.getCheckedSelector(selector[i])).val();
                }
                return items;
            };
        }
        return data;
    }
    
    FormAssist.prototype.getCheckedSelector = function(selector) {
        if (this.inArray($(selector).attr('type'), ['checkbox', 'radio'])) {
            selector = selector+':checked';
        }
        return selector;
    }

    FormAssist.prototype.inArray = function(needle, haystack) {
        var length = haystack.length;
        for(var i = 0; i < length; i++) {
            if(haystack[i] == needle) return true;
        }
        return false;
    }
    
    FormAssist.prototype.objectKeys = function (obj) {
        var length = 0;
        for ( var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                length++;
            }
        }
        return length;
    }
    
    $.fn.FormAssist = function(cb, url) {
        return new FormAssist(this, cb, url);
    }

}( jQuery ));
