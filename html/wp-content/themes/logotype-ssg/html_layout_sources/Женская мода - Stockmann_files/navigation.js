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

Behaviour.mouseover_menu = function (node) {
 	var ul = node.getElementsByTagName("ul")[0].style.display = "block";
 	
 	if (closeMenu && node.getElementsByTagName("ul")[0] == selectedUl) 
 		clearTimeout(closeMenu);
 		
 	selectedUl = node.getElementsByTagName("ul")[0];
 }

 Behaviour.mouseout_menu = function (node) {
 	//var ul = node.getElementsByTagName("ul")[0].style.display = "none";
 	
 	var ul = node.getElementsByTagName("ul")[0];
 	closeMenu = setTimeout("hideSelected('" + ul.id + "')", 100);
 }
 
 Event.observe(window, 'load', createMenu);
 
 function hideSelected(ulId)
 {
 	var ul = document.getElementById(ulId);
 	if (ul)
 		ul.style.display = "none";
 }
 
 var closeMenu;
 var selectedUl;
 
 function createMenu() {
 	var menus = $$('#navi ul > li');
 	var width = 0;
 	
 	menus.each(function (item) { 
 		
 		item.onmouseover = function () {
 			if (this.getElementsByTagName("ul")[0])
 			{
 				this.getElementsByTagName("ul")[0].style.display = "block";
 				
 				if (closeMenu && this.getElementsByTagName("ul")[0] == selectedUl) clearTimeout(closeMenu);
 				selectedUl = this.getElementsByTagName("ul")[0];
 			}
 		}
 		item.onmouseout = function () {
 			if (this.getElementsByTagName("ul")[0])
 			{
 				var ul = this.getElementsByTagName("ul")[0];
 				closeMenu = setTimeout("hideSelected('" + ul.id + "')", 1);
 				//this.getElementsByTagName("ul")[0].style.display = "none";
 			}
 		}
 	 });

 }
 
 

}
/*
     FILE ARCHIVED ON 10:24:03 May 04, 2013 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 10:58:39 Feb 04, 2023.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 87.19
  exclusion.robots: 0.064
  exclusion.robots.policy: 0.057
  cdx.remote: 0.051
  esindex: 0.006
  LoadShardBlock: 59.566 (3)
  PetaboxLoader3.datanode: 153.676 (5)
  CDXLines.iter: 19.595 (3)
  load_resource: 239.0 (2)
  PetaboxLoader3.resolve: 91.357 (2)
*/