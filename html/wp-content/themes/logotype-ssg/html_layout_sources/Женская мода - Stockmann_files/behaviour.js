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

/* Behaviour v 2.1 
 * (c) 2007 Ambientia Ltd.
 *
 * usage HTML:
 *
 * <p func="myFunctionName" anyAttribute="optional,attributes,can,be,given"> Foo & Bar </p>
 *
 * usage javascript:
 *
 * Behaviour.list.mousedown_myFunctionName = function (node) {
 *    // the first param is the event source
 *    var clickedNode = node;
 *    //get your attributes this way
 *    var myAttributes = node.getAttribute('myAttributes');
 *    // do something...
 * }
 *
 */


var Behaviour = {
    list : {},

    handle : function (event)
    {
        //IE compability
        if (!event) event = window.event;

        try { 
              //cancel event if there is behaviour
              if (Behaviour.trigger(event.srcElement || event.target, event.type)) {
                  Event.stop(event);
              }
         
         } catch (err) {}
    },

    trigger : function (node, eventType)
    {
        do { 
                try {
                    if (node.getAttribute('func')) {
                        var hashName = eventType + "_" + node.getAttribute('func');
                        if (this[hashName]) this[hashName](node);
                        else this.list[hashName](node);
                        return true;
                        break;
                    } else {
                    	                   	
                        var hashName = eventType + "_" + (node.id ? node.id : '') + "_" + (node.className ? node.className : '');
                        this.list[hashName](node);
                   
                        
                        return node.breakChain;
                        break;
                    }
                } catch (err) {} 
        } while ( (node = node.parentNode) )
        return false;
    }
}

Event.observe(window, "load", function () {
    Event.observe(document.body, "click", Behaviour.handle);
    Event.observe(document.body, "mousedown", Behaviour.handle);
    Event.observe(document.body, "mouseover", Behaviour.handle); 
    Event.observe(document.body, "mouseout", Behaviour.handle); 
});

function showMenu() {
	if (hider.tout) {
		clearTimeout(hider.tout);
	}
	
	if (!this.menu) {
		this.menu = $( this.getElementsByTagName('ul')[0] );
		if (!this.menu) return;
		document.body.appendChild(this.menu);
		this.menu.className = "menu";
		var offs = $(this).cumulativeOffset();
		this.menu.setStyle( 
			{
				position:"absolute",
				top:(offs[1]+20)+"px",
				left:offs[0]+"px"
			}
		);
		this.menu.onmouseout = hideMenu;
	}
	this.menu.show();
	if (hider.menu && hider.menu != this.menu)  {hider.menu.hide(); hider.menu = null };
	hider.menu = this.menu;
}

var hider = {}

function hideMenu() {;
	if (!hider.menu) return;
	if (hider.tout) clearTimeout(hider.tout);
	hider.tout = setTimeout(function() { hider.menu.hide(); hider.menu = null; }, 300);
}


}
/*
     FILE ARCHIVED ON 21:43:39 May 03, 2013 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 10:58:40 Feb 04, 2023.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 124.071
  exclusion.robots: 0.088
  exclusion.robots.policy: 0.079
  cdx.remote: 0.065
  esindex: 0.01
  LoadShardBlock: 82.739 (3)
  PetaboxLoader3.datanode: 103.663 (5)
  CDXLines.iter: 26.88 (3)
  load_resource: 304.31 (2)
  PetaboxLoader3.resolve: 139.063 (2)
*/