<script language="javascript">
<!-- preload loading gif, so the loading gif doesn't have to load -->
  loading= new Image(16,16)
  loading.src = "/extensions/shoutbox_pun/loading.gif"
  del= new Image(16,16)
  loading.src = "/extensions/shoutbox_pun/cross.png"
</script>
<script type="text/javascript" src="/extensions/shoutbox_pun/jquery.js"></script>
<script language="javascript">

  var t;
  waitTime = 1000;
  lastid = 0;  
  
  $("#loading").hide();
  
    $(document).ready(function(){ 
       
		
		$("form#shoutform").submit(function(){  

				
				 $("#loading").fadeIn();
				 			 
				 $.get("data.php",{  add: $("#shout").val(), id: lastid }, function(xml)
				 {
				 });
				 $("#shout").attr("value","");
			     
				 
				 
				 return false;   
		 });
		 
		 updateShoutbox();
		 
		 
   });   

 
   function addMessages(xml) {
   
		   
		  
		   if($("newones",xml).text() == "0") {
				t = setTimeout("updateShoutbox()", waitTime);   
				return;
				}

			lastid = $("lastid",xml).text(); 				
		 
			$("message",xml).each(function(id) {   
			   message = $("message",xml).get(id); 
			   var d = new Date(parseInt($("date",message).text())*1000);
			   
			   var hour = d.getHours();
			   if(hour.length == 1) hour = "0" + hour;
			   
			   var min = d.getMinutes();
			   if(min.length == 1) min = "0" + min;
				
				
			   $("#shoutbox").prepend(
							
							"<b title=\""+
							d.toDateString()
							+"\">["+
							hour
							+":"+
							min
							+"]</b> <b>"
							+$("username",message).text()+   
							 "</b>: "+$("text",message).text()+   
							 "<br />");
			 });
			 $("#loading").fadeOut();
			 t = setTimeout("updateShoutbox()", waitTime);   
			}
			 
   
		function updateShoutbox() {   
    
		$.get("data.php",{ m: "list", id: lastid }, function add(xml) {
		addMessages(xml)
		});   
	 
     
   }
   

</script>

<form id="shoutform">
		<input type="text" class="sbox" id="shout" color="white" size="100" maxlength="250">
		<img src="/extensions/shoutbox_pun/loading.gif" alt="Loading..." id="loading" />
		<span id="error"></span>
    <div class="sbox">
    <span id="shoutbox">
    </span>
    </div>
</form>
