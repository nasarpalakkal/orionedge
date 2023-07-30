function bell_notification()
{
function load_unseen_notification(view = '')
 {	
  $.ajax({
   url:"header_fetch.php",
   method:"POST",
   data:{view:view},
   dataType:"json",
   success:function(data)
   {	  
    //$('.dropdown-menu').html(data.notification);
    $('.headerMessage').html(data.notification);
	if(data.unseen_notification > 0)
    {     
	 $('.count').html(data.unseen_notification);
    }
	else
	{
		$('.bellicons').hide();
	}
   }
  });  
 }
 
 load_unseen_notification();
 
 /*$('#comment_form').on('submit', function(event){
  event.preventDefault();
  if($('#subject').val() != '' && $('#comment').val() != '')
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     $('#comment_form')[0].reset();
     load_unseen_notification();
    }
   });
  }
  else
  {
   alert("Both Fields are Required");
  }
 });
 */
 $(document).on('click', '.dropdown-toggle', function(){
  $('.count').html('');
	load_unseen_notification('yes');	
 });
 
 setInterval(function(){ 
  load_unseen_notification();; 
 }, 5000);
 
}

function step_admin_logout()
{		
function step_admin_logout1()
	{
var Hours_expirty=document.getElementById("Hours_expirty").value;
var d = Date.parse(Hours_expirty);
var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var c=date+' '+time;
var c1=Date.parse(c);
	if(Hours_expirty!="")
	{
		if(c1>d)
		{
		window.location='logout.php';	
		}	
	}
	
	}
	
	setInterval(function(){ 
  step_admin_logout1();;
 }, 5000);
}