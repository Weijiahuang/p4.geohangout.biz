<br>
<br>
<!-- map  -->
<div id="mapContainer" ></div>

<!-- search bar -->
<div class="light">
	<form action="/posts/search" method="get">
		<input id= "searchinterest" type="text" name="interest" class="search rounded" placeholder="Search by interest" autocomplete="on"> 
		<input id="searchlocation" type="text" name="place" class="search square" placeholder="Search by Location" autocomplete="on">
		<input id="searchbutton" class="participation" type="submit" name="search" value="Search Now" >
	</form>
</div>


<!-- prfoile picture  -->
<img id="profilepicture" src= "/uploads/<?=$user->picture;?>" ><br>

<!-- Pop up windows -->
 <div class="pop_windows" id="dialog-form" title="Make an event">
   <p class="validateTips">All form fields are required.</p>
   <form name="eventForm" id="eventForm" method="post" action="/posts/p_add">
      <fieldset>  
      	<label for="interest">Activity</label>
	  	<img id="project-icon"  src="../img/default.jpg" class="ui-state-default" alt="">
	  	<input type="text" name="interest" id="interest" class="text ui-widget-content ui-corner-all interest">
	  	<input type="hidden" id="project-id">
	  	<p id="project-description"></p>
	  	<label for="date">Date</label>
	  	<input type="text" name="date" id="date" value="<?=date("D, F d, Y")?>"  class="text ui-widget-content ui-corner-all">
        <input type="text" name="time" id="time" placeholder="Time?" class="text ui-widget-content ui-corner-all">
        <label for="place">Location</label>
        <input type="text" name="place" id="place" placeholder="Enter the location"
             onFocus="geolocate()" class="text ui-widget-content ui-corner-all">
        
        <label for="moreinfo">More info (Optional)</label>
        <input type="text" name="content" id="moreinfo" placeholder="Event description"
		class="text ui-widget-content ui-corner-all">    
            
         <br>
         Event available to
        <select name="group_category">
         <option value="Everyone">All the followers</option>
         <option value="Public">Public</option>

         <option value="Friends">Friends</option>
         <option value="Family">Family</option>
         <option value="Acquaintance">Acquaintance</option>
       </select>
       
        Send email to 
        <select name="group">
        <option value=""> </option>
         <option value="Everyone">Everyone</option>
         <option value="Friends">Friends</option>
         <option value="Family">Family</option>
         <option value="Acquaintance">Acquaintance</option>
       </select>
       
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       	Send SMS to &nbsp;
       	<select name="groupmessage">
        <option value=""> </option>
         <option value="Everyone">Everyone</option>
         <option value="Friends">Friends</option>
         <option value="Family">Family</option>
         <option value="Acquaintance">Acquaintance</option>
       </select>
       
       
      </fieldset>
   </form>
</div>
 
<!-- Top menu -->
 
<div id='profilemenu' >
   <div id ="brandname" >
			<strong>   Spur!</strong>
   </div>
   <div id ="mainpage" >
       <!-- Menu for users who are logged in -->
       <?php if($user): ?>
            <a id="logout" href='/users/logout'>Logout</a>       
            <a id ="profile" href='/users/profile'>Profile</a>            
			<button id ="create-user" class="navigation">  Add a post</button>
			<a  class="navigation" href='/posts/index'>View posts<a>
			<a class="navigation" href='/posts/users'>Users<a>
			<a class="navigation" href ='/posts/followers'> Followers			<a>
			<a class="navigation" href ='/posts/following'> Following	<a>
          
       <!-- Menu options for users who are not logged in -->
       <?php else: ?>
           <a href='/users/signup'>Sign Up</a>
           <a href='/users/index'>Log In</a>
       <?php endif; ?>
   </div>
</div>
<br>


<!-- bulletin board -->

<div id ='windows'>
    <h1 >Current Posts:</h1>
       
       <?php foreach($posts as $post): ?>
       <hr>
       <div id = "box" >
          <img id="postpicture" src= "/uploads/<?=$post['picture'];?>" >        
         
          <!-- activity content -->
          <div id="eventfont" >      
          	<strong id="activity">Activity:  <?=$post['interest']?> </strong><br>
		  	Time: <?=$post['date']?> at <?=$post['time']?><br>
		  	Place: <?=$post['place']?> <br>
		  	<time datetime="<?=Time::display($post['created'],'Y-m-d G:i')?>">
		  		Posted at <?=Time::display($post['created'])?>
		  	</time>
		  </div>     
          <div id="name" >
              <div id="username" ><?=$post['first_name']?> <?=$post['last_name']?> </div>&nbsp;&nbsp;&nbsp;&nbsp;
           
	          </div>			
          <div>
      
		  <?php if(isset($join_connections[$post['post_id']])): ?>
    	  <!-- If there exists a connection with this user, show a unfollow link -->		
		  <div id="<?=$post['post_id']?>" class="jr-btn-container">
	      <a class = "participation jr-btn" id ="remove" href='<?=$post['post_id']?>' > Remove </a>
	      </div>
               
          <!-- Otherwise, show the follow link -->
          <?php else: ?>
    		<div id="<?=$post['post_id']?>" class="jr-btn-container">
	         <a class = "participation jr-btn" href='<?=$post['post_id']?>' >I want to join</a>
	         </div>	             
          <?php endif; ?>

     	 <!--send email-->
         <a id="sendmail" href="mailto:<?=$post['email']?>?Subject=[Spur]%20I%20want%20to%20join%20you" target="_top">Send Mail</a>
       </div>             
   </div>
<?php endforeach; ?>

</div>



<!-- Google Address autocomplete API -->
    
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    
<body onload="initialize()">

<script type="text/javascript" src="/js/jquery.js"> </script>
<script type="text/javascript" src="/js/nav.js"> </script>




