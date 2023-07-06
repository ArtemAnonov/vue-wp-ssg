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

var blocked = false;
function showHide(id)
{
	if (blocked != false)
	{		
		blocked.style.display = 'none';
		blocked = false;
	}
	
    obj = document.getElementById(id);
	if (obj)
	{
    	obj.style.display = 'block';
    	blocked = obj;
    }
}

function clearInput(text,inputObj)
{
	if (inputObj.value==text)
		inputObj.value = '';
}

function selectDepartmentStore(select)
{
	if (select.selectedIndex)
	{
		var val = select.options[select.selectedIndex].value;
		val = val.replace(/\r\n/g,"\n");
		window.location = val;
	}
}

function CorrectEmailaddress(SubscriberForm) {
	if (SubscriberForm.pf_Email.value.length > 255) {
		alert("Please enter at most 255 characters in the \"Email address\" field.");
		SubscriberForm.pf_Email.focus();
		return (false);
	}
	if (SubscriberForm.pf_Email.value == "") {
		alert("Please enter a value for the \"Email address\" field.");
		SubscriberForm.pf_Email.focus();
		return (false);
	}
	if (SubscriberForm.pf_Email.value.length < 7) {
		alert("Please enter at least 7 characters in the \"Email address\"field.");
		SubscriberForm.pf_Email.focus();
		return (false);
	}
	pf_Email = SubscriberForm.pf_Email.value
	at = pf_Email.indexOf("@");
	lastat = pf_Email.lastIndexOf("@");
	dot = pf_Email.lastIndexOf(".");
	if (at < 1 || at != lastat || dot < at) {
		alert("Your email address is not correct. Please try again.");
		return (false);
	}
}

/* This replaces media place holder images with mediaplayers */


(function ($) {
 
 	$(
 		function()
 		{
 			$("img.mediaHolder").each(
 				function (index) {
 					var a = this.id.split('|');
					var playerType = a[0];
					var src = a[1];
					var id = "media_"+index;
					var w = this.width;
					var h = this.height
				
					var div = $('<div class="mediaholder"></div>').css({ width:w+"px", height:h+"px", display:'inline' });
				
					$(this).hide();
					$(this).before( div );
					
				try {
					switch ( playerType )
					{
						case "flash" :
							div.html( new SWFObject(src, id, w, h, 8).getSWFHTML () );							
							break;
						case "mediaplayer" :
						case "youtube" :
							var mp = new SWFObject("/js/ced/lib/player.swf",id,w,h,9);
							mp.addParam("allowfullscreen","true");
							mp.addVariable("width",w);
							mp.addVariable("height",h);
							mp.addVariable("file",src);
							div.html(mp.getSWFHTML());
							break;
						case "wmvplayer" :
							new jeroenwijering.Player(div.get(0), '/js/ced/lib/wmvplayer.xaml', { file : src, height : h+"", width : w+"" } );
							break;
					}
				} catch (error) {
						alert("Error embedding media player for "+playerType+" : "+src+"\n"+error);
				}
		
				
 				}
 			
 			);
 		}
 	
 	)
 
 
 })(jQuery);


}
/*
     FILE ARCHIVED ON 23:16:07 May 02, 2013 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 10:58:40 Feb 04, 2023.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 106.636
  exclusion.robots: 0.075
  exclusion.robots.policy: 0.067
  cdx.remote: 0.057
  esindex: 0.009
  LoadShardBlock: 72.872 (3)
  PetaboxLoader3.datanode: 129.055 (5)
  CDXLines.iter: 20.972 (3)
  load_resource: 173.767 (2)
  PetaboxLoader3.resolve: 54.904 (2)
*/