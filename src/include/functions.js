/*menu hover*/
//set class checkbox to input type checkbox
function appendInputTypeClasses() {
 if ( !document.getElementsByTagName )
 return;
var inputs = document.getElementsByTagName('input');
var inputLen = inputs.length;
 for ( i=0;i<inputLen;i++ ) {
    if ( inputs[i].className!="signup" && inputs[i].getAttribute('type') && (inputs[i].getAttribute('type')=='submit' || inputs[i].getAttribute('type')=='checkbox'|| inputs[i].getAttribute('type')=='radio'|| inputs[i].getAttribute('type')=='reset'))
  inputs[i].className += inputs[i].getAttribute('type');
 }
}
	function startList_sub() {

		if (document.all&&document.getElementById) {
		navRoot = document.getElementById("navigation");
		for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName=="LI") {
				 node.onmouseover=function() {
						this.className+="sub_over";
					this.style._marginLeft="-100px";

				  }
				  node.onmouseout=function() {
						this.className=this.className.replace("sub_over", "");
				   }

				  for (j=0; j<node.childNodes.length; j++) {
							subul = node.childNodes[j];
							if (subul.nodeName=="UL") {
							//	subnode = subul.childNodes[j];
									  for (t=0; t<subul.childNodes.length; t++) {
									  		subitem = subul.childNodes[t];
												if (subitem.nodeName=="LI") {
													subitem.onmouseover=function() {
														this.className+="over";
														this.style._marginLeft="-100px";
														this.style.backgroundColor="#348DC9";
													}
													subitem.onmouseout=function() {
														this.className=this.className.replace("over", "");
														this.style.backgroundColor="#38506F";
													 }
												}
												for (k=0; k<subitem.childNodes.length; k++) {
													subsubitem = subitem.childNodes[k];
														if (subsubitem.nodeName=="UL") {
															for (h=0; h<subsubitem.childNodes.length; h++) {
																subsubsubitem = subsubitem.childNodes[h];
																	if (subsubsubitem.nodeName=="LI") {
																			subsubsubitem.onmouseover=function() {
																				this.style.backgroundColor="#348DC9";
																			}
																			subsubsubitem.onmouseout=function() {
																				this.style.backgroundColor="#38506F";
																			 }
																	}
															  }
														  }
												 }
										}
							}
				   		}
			   }
		  }
		 }
		 appendInputTypeClasses();
		}
/*header photoes swirling*/
var c=2
var s
function photoGallery()
{
   if (c%6==0){
      document.getElementById('h1').src = "./styles/new/images/mt_home_header_footbal1.jpg";
      document.getElementById('h2').src = "./styles/new/images/mt_home_header_footbal2.jpg";
      document.getElementById('h3').src = "./styles/new/images/mt_home_header_footbal3.jpg";
      document.getElementById('h4').src = "./styles/new/images/mt_home_header_footbal4.jpg";
      document.getElementById('h5').src = "./styles/new/images/mt_home_header_footbal5.jpg";
   }
   if (c%6==1){
      document.getElementById('h1').src = "./styles/new/images/mt_home_header_footbal11.jpg";
      document.getElementById('h2').src = "./styles/new/images/mt_home_header_footbal21.jpg";
      document.getElementById('h3').src = "./styles/new/images/mt_home_header_footbal31.jpg";
      document.getElementById('h4').src = "./styles/new/images/mt_home_header_footbal41.jpg";
      document.getElementById('h5').src = "./styles/new/images/mt_home_header_footbal51.jpg";
   }
   if (c%6==2){
      document.getElementById('h1').src = "./styles/new/images/mt_home_header_footbal12.jpg";
      document.getElementById('h2').src = "./styles/new/images/mt_home_header_footbal22.jpg";
      document.getElementById('h3').src = "./styles/new/images/mt_home_header_footbal32.jpg";
      document.getElementById('h4').src = "./styles/new/images/mt_home_header_footbal42.jpg";
      document.getElementById('h5').src = "./styles/new/images/mt_home_header_footbal52.jpg";
   }
   if (c%6==3){
      document.getElementById('h1').src = "./styles/new/images/mt_home_header_footbal13.jpg";
      document.getElementById('h2').src = "./styles/new/images/mt_home_header_footbal23.jpg";
      document.getElementById('h3').src = "./styles/new/images/mt_home_header_footbal33.jpg";
      document.getElementById('h4').src = "./styles/new/images/mt_home_header_footbal43.jpg";
      document.getElementById('h5').src = "./styles/new/images/mt_home_header_footbal53.jpg";
   }
   if (c%6==4){
      document.getElementById('h1').src = "./styles/new/images/mt_home_header_footbal14.jpg";
      document.getElementById('h2').src = "./styles/new/images/mt_home_header_footbal24.jpg";
      document.getElementById('h3').src = "./styles/new/images/mt_home_header_footbal34.jpg";
      document.getElementById('h4').src = "./styles/new/images/mt_home_header_footbal44.jpg";
      document.getElementById('h5').src = "./styles/new/images/mt_home_header_footbal54.jpg";
   }
   if (c%6==5){
      document.getElementById('h1').src = "./styles/new/images/mt_home_header_footbal15.jpg";
      document.getElementById('h2').src = "./styles/new/images/mt_home_header_footbal25.jpg";
      document.getElementById('h3').src = "./styles/new/images/mt_home_header_footbal35.jpg";
      document.getElementById('h4').src = "./styles/new/images/mt_home_header_footbal45.jpg";
      document.getElementById('h5').src = "./styles/new/images/mt_home_header_footbal55.jpg";
   }
   c=c+1
   s=setTimeout("photoGallery()",20000)
}
function Ajax() {
   this.req = null;
   this.url = null;
   this.status = null;
   this.statusText = '';
   this.method = 'GET';
   this.async = true;
   this.dataPayload = null;
   this.readyState = null;
   this.responseText = null;
   this.responseXML = null;
   this.handleResp = null;
   this.responseFormat = 'text', // 'text', 'xml', 'object'
   this.mimeType = null;
   this.headers = [];
   this.init = function() {
      var i = 0;
      var reqTry = [
      function() { return new XMLHttpRequest(); },
      function() { return new ActiveXObject('Msxml2.XMLHTTP') },
      function() { return new ActiveXObject('Microsoft.XMLHTTP' )} ];

      while (!this.req && (i < reqTry.length)) {
         try {
            this.req = reqTry[i++]();
         }
         catch(e) {}
      }
      return true;
   };
   this.doGet = function(url, hand, format) {
      this.url = url;
      this.handleResp = hand;
      this.responseFormat = format || 'text';
      this.doReq();
   };
   this.doPost = function(url, dataPayload, hand, format) {
      this.url = url;
      this.dataPayload = dataPayload;
      this.handleResp = hand;
      this.responseFormat = format || 'text';
      this.method = 'POST';
      this.doReq();
   };
   this.doReq = function() {
      var self = null;
      var req = null;
      var headArr = [];

      if (!this.init()) {
         alert('Could not create XMLHttpRequest object.');
         return;
      }
      req = this.req;
      req.open(this.method, this.url, this.async);
      if (this.method == "POST") {
         this.req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      }
      if (this.method == 'POST') {
         req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      }
      self = this;
      req.onreadystatechange = function() {
         var resp = null;
         self.readyState = req.readyState;
         if (req.readyState == 4) {

            self.status = req.status;
            self.statusText = req.statusText;
            self.responseText = req.responseText;
            self.responseXML = req.responseXML;

            switch(self.responseFormat) {
               case 'text':
               resp = self.responseText;
               break;
               case 'xml':
               resp = self.responseXML;
               break;
               case 'object':
               resp = req;
               break;
            }

            if (self.status > 199 && self.status < 300) {
               if (!self.handleResp) {
                  alert('No response handler defined ' +
                  'for this XMLHttpRequest object.');
                  return;
               }
               else {
                  self.handleResp(resp);
               }
            }

            else {
               self.handleErr(resp);
            }
         }
      }
      req.send(this.dataPayload);
   };
   this.abort = function() {
      if (this.req) {
         this.req.onreadystatechange = function() { };
         this.req.abort();
         this.req = null;
      }
   };
   this.handleErr = function() {
      var errorWin;
      // Create new window and display error
      try {
         errorWin = window.open('', 'errorWin');
         errorWin.document.body.innerHTML = this.responseText;
      }
      // If pop-up gets blocked, inform user
      catch(e) {
         alert('An error occurred, but the error message cannot be' +
         ' displayed because of your browser\'s pop-up blocker.\n' +
         'Please allow pop-ups from this Web site.');
      }
   };
   this.setMimeType = function(mimeType) {
      this.mimeType = mimeType;
   };
   this.setHandlerResp = function(funcRef) {
      this.handleResp = funcRef;
   };
   this.setHandlerErr = function(funcRef) {
      this.handleErr = funcRef;
   };
   this.setHandlerBoth = function(funcRef) {
      this.handleResp = funcRef;
      this.handleErr = funcRef;
   };
   this.setRequestHeader = function(headerName, headerValue) {
      this.headers.push(headerName + ': ' + headerValue);
   };
}
function LoadPlayerInDiv(prefix, pID)
{
   try
   {
      doShowHide(prefix + pID);
      //getProgressBar(prefix + pID);
      getPlayer(pID, prefix);
   }
   catch(ex) { }
}
function doShowHide(pPositionName)
{
   try
   {
      var bCurrentlyShowing = ((document.getElementById(pPositionName).style.display=='none')?true:false);
      document.getElementById(pPositionName).style.display = ((bCurrentlyShowing)? '': 'none');
   }
   catch(ex){}
}
function getPlayer(pID, prefix)
{
   try
   {
      if (document.getElementById('td_' + prefix + pID).innerHTML.length < 70)
      {
         var handle_getPlayer_response = function(str)
         {
            document.getElementById('td_' + prefix + pID).innerHTML = str;
         }
         var ajax = new Ajax();
         var strUrl = 'aj_get_player.php?id=' + pID;
         ajax.doGet(strUrl, handle_getPlayer_response, 'text');
      }
   }
   catch(ex){}
}
function doShow(pPositionName)
{
   try{document.getElementById(pPositionName).style.display = '';}catch(ex){}
}
function doHide(pPositionName)
{
   try{document.getElementById(pPositionName).style.display = 'none';}catch(ex){}
}
function showHideSelectionField(origin)
{
   try
   {
      var theId = origin.parentNode.id;
      var sel = theId.substring(1, theId.length-1);
      var SelField = document.getElementById(sel);
      SelField.style.display = (SelField.style.display=='none')? '': 'none';
      //alert(sel);
      /*
      playerTagId = origin.parentNode.id;
      strSel = playerTagId.substring(6);
      var currentPlayerSelectField = document.getElementById('sel_'+strSel );
      currentPlayerSelectField.style.display = (currentPlayerSelectField.style.display=='none')? '': 'none';
      for(var i = 1; i <= 16; i++)
      {
      var id = 'sel_'+i;
      if(id != currentPlayerSelectField.id) document.getElementById(id).style.display='none'
      }
      */
   }
   catch(ex)
   {
      //alert(ex);
   }
}
function updatePlayer(origin)
{
   try
   {
      var dropdownValue = origin[origin.selectedIndex].text;
      var NumberField = document.getElementById("___" + origin.id + "_number___");
      NumberField.innerHTML = dropdownValue.substring(0, dropdownValue.indexOf('.'));
      var NameField = document.getElementById("___" + origin.id + "_name___");
      NameField.innerHTML = dropdownValue.substring(dropdownValue.indexOf('.')+2, dropdownValue.length);

   }
   catch(ex)
   {
      alert(ex);
   }
}
/*
function updatePlayer(origin)
{
try
{
playerTagId = origin.parentNode.id;
strSel = playerTagId.substring(6);
var currentPlayerSelectField = document.getElementById('sel_'+strSel);
currentPlayerSelectField.style.display = 'none';
var id = currentPlayerSelectField.options[currentPlayerSelectField.selectedIndex].value;
var text = currentPlayerSelectField.options[currentPlayerSelectField.selectedIndex].text;
var textNumber = text.substring(0, 2);
var textName = text.substring(3);
var possibleField = currentPlayerSelectField.nextSibling;
while(possibleField.nodeName != 'DIV')	possibleField = possibleField.nextSibling;
var playerDiv = possibleField;
possibleField = playerDiv.firstChild;
while(possibleField.nodeName != 'DIV')	possibleField = possibleField.nextSibling;
var firstDivField = possibleField;
{
var possibleNumberField = firstDivField .firstChild;
while(possibleNumberField.nodeName != 'DIV')
possibleNumberField = possibleNumberField.nextSibling;
possibleNumberField.innerHTML = textNumber;
}
var currentPlayerTag = document.getElementById(playerTagId);
var classNameParts = currentPlayerTag.className.split(' ');
var newClassNames = new Array();
for(var i = 0; i < classNameParts.length; i++)
{
if(classNameParts[i].substr(0, 8) == 'selected')
{
if(id == 0)
{
newClassNames.push('selected_none');
}
else for(var j = 0; j < playerTypes.length; j++)
{
if(playerTypes[j][0] == id)
{
newClassNames.push('selected_'+playerTypes[j][1]);
break;
}
}
}
else
{
newClassNames.push(classNameParts[i]);
}
}
currentPlayerTag.className = newClassNames.join(' ');
possibleField = possibleField.nextSibling;
while(possibleField.nodeName != 'DIV')	possibleField = possibleField.nextSibling;
var secondDivField = possibleField;
{
possibleNameField = secondDivField.firstChild;
while(possibleNameField.nodeName != 'DIV')
possibleNameField = possibleNameField.nextSibling;
possibleNameField.innerHTML = textName;
}
}
catch(ex)
{
//alert(ex);
}
}
*/
