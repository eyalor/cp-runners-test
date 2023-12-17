/*
 * jQuery autoResize (textarea auto-resizer)
 * @copyright James Padolsey http://james.padolsey.com
 * @version 1.04
 */

(function($){
    
    $.fn.autoResize = function(options) {

        // Just some abstracted details,
        // to make plugin users happy:
        var settings = $.extend({
            extraSpace : 20
        }, options);
        
        // Only textarea's auto-resize:
        this.filter('textarea').each(function(){
            
            // Get rid of scrollbars and disable WebKit resizing:
            var textarea = $(this).css({resize:'none','overflow-y':'hidden'}),
            
                // Cache original height, for use later:
                origHeight = textarea.height(),

                // handling new lines
                newLineRegEx = /\n/g,
                newLineHTML = '<br>',

                // Need clone of textarea, hidden off screen:
                clone = (function(){
                    
                    // Properties which may effect space taken up by chracters:
                    var props = ['width','font-family','font-size','lineHeight','textDecoration','letterSpacing'],
                        propOb = {};
                        
                    // Create object of styles to apply:
                    $.each(props, function(i, prop){
                        propOb[prop] = textarea.css(prop);
                    });

                    // Clone the actual textarea removing unique properties
                    // and insert before original textarea:
                    return jQuery('<div/>').css('display', 'none').css('word-wrap', 'break-word').css(propOb).insertBefore(textarea);

                })(),
                updateSize = function() {

                    var content = $(this).val().replace(newLineRegEx, newLineHTML);
                    clone.html(content);

                    if (content == '')
                    {
                        var newHeight = origHeight;
                    }
                    else
                    {
                        var newHeight = Math.max(textarea.height(), clone.height() + settings.extraSpace);
                    }

                    textarea.css('height', newHeight);
                };

            // Bind namespaced handlers to appropriate events:
            textarea
                .unbind('.dynSiz')
                .bind('keyup.dynSiz', updateSize)
                .bind('keydown.dynSiz', updateSize)
                .bind('change.dynSiz', updateSize);
        });
        
        // Chain:
        return this;
        
    };
    
    
    
})(jQuery);