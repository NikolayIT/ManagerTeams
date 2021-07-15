<?php
function get_invite_link_info()
{
	global $USER;
}
function adv_center_middle()
{
?>
<script type="text/javascript" charset="utf-8" >
//<![CDATA[
var EtargetSearchQuery = '';//OPTIONAL_PAGE_URL

var EtargetBannerIdent = 'ETARGET-bg-18895-728x90-IFRAME';
var EtargetBannerStyle = '&tabl=4&logo=1&logo_type=5&left=1&title_color=0066d5&h_title_color=0066d5&title_underline=1&h_title_underline=1&font=verdana&fsi=12&background_color=transparent&nourl=0&background_opacity=100&hover_back=transparent&border_color=ffffff&border_style=none&border_radius=5&text_color=000000&url_color=0066d5&h_text_color=000000&h_url_color=0066d5&url_underline=0&h_url_underline=1';

function etargetScript(){this.cs='utf-8';this.it='';this.S=null;this.I=null;this.fC=function(it,id){var D=document;var aB=D.getElementsByTagName('body');var sS=D.getElementsByTagName('script');for(var i=0;i<sS.length;i++){try{if(sS[i].innerHTML.match(it)){this.S=sS[i];this.it=it;if(this.S.charset)this.cs=this.S.charset;this.I=D.createElement('iframe');this.I.setAttribute('id',id);if(aB.length<1){var B=D.createElement('body');D.documentElement.appendChild(B);B.appendChild(this.I);B.style.margin='0px';B.style.borderWidth='0px';}else{this.S.parentNode.insertBefore(this.I,this.S);}return this.I;break;}}catch(err){}}},this.iS=function(){if(this.it!=''){var a=this.it.split('-');this.D=a[1];this.R=a[2];this.A=a[3];var aa=this.A.split('x');this.W=aa[0];this.H=aa[1];this.I.setAttribute('width',this.W+'px');this.I.setAttribute('height',this.H+'px');this.I.setAttribute('marginwidth','0');this.I.setAttribute('marginheight','0');this.I.setAttribute('vspace','0');this.I.setAttribute('hspace','0');this.I.setAttribute('allowTransparency','true');this.I.setAttribute('frameborder','0');this.I.setAttribute('scrolling','no');this.I.style.borderWidth='0px';this.I.style.overflow='hidden';this.I.style.display='block';this.I.style.margin='0px';this.I.style.width=this.W+'px';this.I.style.height=this.H+'px';this.I.setAttribute('charset',this.cs);}},this.iC=function(me,dg,q){if(this.it!=''){this.iS();this.P='http:';if(document.location.protocol=='https:')this.P='https:';var sr='ref='+this.R+'&area='+this.W+'x'+this.H+'&';sr=sr+dg+'&'+me.gA()+'&cs='+this.cs;this.I.setAttribute('src',this.P+'/'+'/'+this.D+'.search.etargetnet.com/generic/generic.php?'+sr+'');}},this.dY=function(){if(this.S)this.S.parentNode.removeChild(this.S);}}function etargetMetaTags(){this.w='';this.k='';this.t='';this.d='';this.q='';this.search_object ='';this.gD=function(){this.k='';this.d='';this.t='';var D=document;if(D.getElementsByTagName){var a=D.getElementsByTagName('meta');for(var i=0;i<a.length;i++){if(a[i].name=='keywords'){this.k=a[i].content;}if(a[i].name=='description'){this.d=a[i].content;}}var a=D.getElementsByTagName('title');for(var i=0;i<a.length;i++){this.t=a[i].innerHTML;}}return this.k;},this.tR=function(s,c){return this.lR(this.rR(s,c),c);},this.lR=function(s,c){c=c||'\\s';return s.replace(new RegExp('^['+c+']+','g'),'');},this.rR=function(s,c){c=c||'\\s';return s.replace(new RegExp('['+c+']+$','g'),'');},this.getValFrom=function(elId){var m=document.getElementById(elId);if(!m)return '';if(typeof(m)=='undefined')return '';if(m==undefined)return '';var rezlt='';if(m.tagName=='A'){r=m.innerHTML;}else if(m.tagName=='SPAN'){r=m.innerHTML;}else if(m.tagName=='DIV'){r=m.innerHTML;}else if(m.tagName=='TD'){r=m.innerHTML;}else if(m.type=='select'){r=m.options[m.selectedIndex].value;}else if(m.type=='radio'){r=m.checked;}else if(m.type=='checkbox'){r=m.checked;}else{r=m.value;}this.q=this.tR(r);if(this.q=='')this.search_object='';return r;},this.sW=function(w){this.w=this.tR(w);},this.sQ=function(q){if(q=='')return false;this.q=this.tR(q);this.search_object='';},this.gM=function(name,cnt,len){var s='';var c=' ';if((this.d=='')&&(this.k=='')&&(this.t==''))this.gD();if((this.search_object!='')&&(this.q==''))this.getValFrom(this.search_object);if(name=='description'){s=this.tR(this.d);}else if(name=='title'){s=this.t;}else if(name=='keywords'){s=this.tR(this.k);c=',';}else if(name=='search_object'){return encodeURIComponent(this.q);}var a=s.split(c);s='';var ss='';var l=a.length;if(l>cnt)l=cnt;for(var i=0;i<l;i++){ss=encodeURIComponent(this.tR(a[i]));if((s.length+ss.length+1)>len)return s;if(s!='')s=s+'+';s=s+ss;}return s;},this.gA=function(){var s='';s=s+'&tt='+this.gM('title',8,60);s=s+'&mk='+this.gM('keywords',8,60);s=s+'&md='+this.gM('description',8,60);if(this.q!='')s=s+'&q='+this.gM('search_object',8,60);else s=s+'&q='+escape(location.href);if(this.w!='')s=s+'&keywords='+this.w;return s;}}if(!EtargetBannerThe)var EtargetBannerThe=1;else EtargetBannerThe++;if(!EtargetMetaTags)var EtargetMetaTags=new etargetMetaTags();EtargetMetaTags.q='';if(typeof(EtargetSearchObject)!='undefined')EtargetMetaTags.search_object=EtargetSearchObject;if(typeof(EtargetSearchQuery)!='undefined')EtargetMetaTags.sQ(EtargetSearchQuery);if(typeof(EtargetCatKeywords)!='undefined')EtargetMetaTags.sW(EtargetCatKeywords);var EtargetScript=new etargetScript();EtargetScript.fC(EtargetBannerIdent,EtargetBannerIdent+EtargetBannerThe);EtargetScript.iC(EtargetMetaTags,EtargetBannerStyle);EtargetScript.dY();
//]]>
</script>
<?php
return "";
   $i = rand(1, 1);
   switch ($i)
   {
   	// easytarder
   	case 1: return "<script src=\"http://ads.easytrader.bg/common_rotator.php?nrid=2&ur=2761&r=\" + escape(document.referrer) type=\"text/javascript\"></script>"; break;
   	// adv.bg
   	case 2: case 3: case 4: return "<script type=\"text/javascript\">
<!--
document.write( '<sc' + 'ript src=\"http://yield.adv.bg/m0.php?i=15&z=1&s=146&r=' + escape( document.referrer ) + '&n=' + (new Date()).getTime() + '\"></sc' + 'ript>' );
//-->
</script>"; break;
   	// diskretni
   	//case 5: return "<a href=\"http://diskretni.com\" target=\"_blank\"><img src=\"http://www.prikachi.com/files/583854n.jpg\"></a><br>"; break;
   	// prepishi
   	//case 6: case 7: return "<center><object width=\"468\" height=\"60\"><param name=\"movie\" value=\"http://managerteams.com/images/prepishi.com_468x60.swf\"><embed src=\"http://managerteams.com/images/prepishi.com_468x60.swf\" width=\"468\" height=\"60\"></embed></object></center>"; break;
   	// music-core.net
   	default: return "<a href=\"http://music-core.net/index.html\" target=\"_blank\"><img src=\"http://music-core.net/static/images/banners/banner_728x90.jpg\"></a><br>";
   }
}
function adv_center_down()
{
   $i = rand(4, 5);
   switch ($i)
   {
   	// 
   	case 1: case 2: ""; break;
   	// diskretni
   	//case 3: return "<a href=\"http://diskretni.com\" target=\"_blank\"><img src=\"http://www.prikachi.com/files/583854n.jpg\"></a><br>"; break;
   	// prepishi
   	case 4: case 5: return "<center><object width=\"468\" height=\"60\"><param name=\"movie\" value=\"http://managerteams.com/images/prepishi.com_468x60.swf\"><embed src=\"http://managerteams.com/images/prepishi.com_468x60.swf\" width=\"468\" height=\"60\"></embed></object></center>"; break;
   	// OnlineMusicDB.net
   	default: return "<a href=\"http://music-core.net/index.html\" target=\"_blank\"><img src=\"http://music-core.net/static/images/banners/banner_728x90.jpg\"></a><br>";
   }
}
function adv_left_down()
{
	$add = "";
   $add .= "<a href=\"http://prepishi.com\" target=\"_blank\"><b>Учебни материали</b></a><br>";
   $add .= "<a href=\"http://music-core.net\" title=\"Безплатна музика\" target=\"_blank\"><b>Безплатна музика</b></a><br>";
   $add .= "<a href=\"http://rubixstudio.com\" target=\"_blank\"><b>Уеб дизайн</b></a><br>";
   $add .= "<a href=\"http://filesdb.net\" target=\"_blank\"><b>Търсачка на файлове</b></a><br>";
   $add .= "<a href=\"http://agency-angel.com\" target=\"_blank\"><b>Сватбена агенция</b></a><br>";
   $add .= "<a href=\"http://renikosa.com\" target=\"_blank\"><b>Удължаване на коса</b></a><br>";
   //$add .= "<a href=\"http://evilx.net\" target=\"_blank\"><b>MP3 Търсачка</b></a><br>";
   //$add .= "<a href=\"http://chaosborn.com\" target=\"_blank\"><b>chaosborn.com</b></a><br>";
   //$add .= "<a href=\"http://ipotpal-ex.com\" title=\"ипотпал\"><b>ипотпал</b><a/><br>";
   $i = rand(1, 2);
   switch ($i)
   {
      // prika4i banner
   	//case 1: case 2: return "{$add}<a href=\"http://futbol-tv.com\" target=\"_blank\"><img src=\"http://managerteams.com/images/banner-131x180.jpg\"/ alt=\"http://futbol-tv.com\"></a>"; break;
   	//
   	default: return "{$add}";
   }
}
?>