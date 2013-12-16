<?php
class posts_controller extends base_controller {

    public function __construct() {
       parent::__construct();

        # Make sure user is logged in if they want to use anything in this controller
        if(!$this->user) {
            die("Members only. <a href='/index/index'>Login</a>");
        }
    }

    public function add() {

        # Setup view
        $this->template->content = View::instance('v_posts_add');
        $this->template->title   = "New Post";

        # Render template
        echo $this->template;

    }

    public function p_add() 
    {
		
        # Associate this post with this user
        $_POST['user_id']  = $this->user->user_id;
       
	    # Unix timestamp of when this post was created / modified
      	$_POST['created']  = Time::now();
        $_POST['modified'] = Time::now();
        $_POST['picture']= $this->user->picture;	
		
        # Insert
        # Note we didn't have to sanitize any of the $_POST data because we're using the insert method which does it for us
        DB::instance(DB_NAME)->insert('posts', $_POST);
        
        Router::redirect('/posts/index');
        
        # Quick and dirty feedback
        echo "Your post has been added. <a href='/posts/add'>Add another</a>";
    }
    
    public function search()
   {
	    $this->template->content = View::instance('v_posts_index');
		$this->template->title   = "All Posts";
		if(isset($_GET['search']))
		{
	     	$search_interest= $_GET['interest'];
	     	$search_place = $_GET['place'];
	     	
		 	$q = 'SELECT
    			posts.interest,
				posts.picture,
				posts.time,
				posts.place, 
				posts.content,
				posts.created,
				posts.user_id AS post_user_id,
				users_users.user_id AS follower_id,
				users.first_name,
				users.last_name
				FROM posts
				INNER JOIN users_users 
				ON posts.user_id = users_users.user_id_followed
				INNER JOIN users 
				ON posts.user_id = users.user_id
				WHERE users_users.user_id = '.$this->user->user_id.' 
				AND posts.interest like "%'.$search_interest.'%"
				AND posts.place like "%'.$search_place.'%" 
				ORDER BY posts.created DESC';
				
				
				# Run the query, store the results in the variable $posts
				$posts = DB::instance(DB_NAME)->select_rows($q);
				
				# Pass data to the View
				$this->template->content->posts = $posts;

				# Render the View
				echo $this->template;
								
		}
    }
    
    
    #  Search for users by their name	 	
	    public function searchName()	 	
	   {	 	
		    $this->template->content = View::instance('v_posts_users');	 	
			$this->template->title   = "Find Users";	 	
			if(isset($_GET['search']))	 	
			{	 	
		     	 $search_name= $_GET['name'];	 		
		     		 	
			 	 	 	
			 	$q = 'SELECT	 	
	    			users.first_name,	 	
					users.last_name	 	
					FROM users	 	
					WHERE users.first_name like "%'.$search_name.'%"	 	
					OR users.last_name like "%'.$search_name.'%" 	 	
					ORDER BY users.created DESC';	 						 	
						 	
					# Run the query, store the results in the variable $users	 	
					$users = DB::instance(DB_NAME)->select_rows($q);	 	
					
					# Pass data to the View	 	
					$this->template->content->users = $users;	 	
		 	
					# Render the View	 	
					echo $this->template; 	 	
					 											 	
			}	 	
	    }
	    
    
    public function index()
    {
    
    # Set up the View
    $this->template->content = View::instance('v_posts_index');
    $this->template->title   = "All Posts";

    # Query
    $q = 'SELECT
    		posts.interest,
    		posts.post_id,
    		posts.picture,
    		posts.time,
    		posts.place, 
            posts.content,
            posts.created,
            posts.user_id AS post_user_id,
            users_users.user_id AS follower_id,
            users.first_name,
            users.email,
            users.last_name
        FROM posts
        INNER JOIN users_users 
            ON posts.user_id = users_users.user_id_followed
        INNER JOIN users 
            ON posts.user_id = users.user_id ORDER BY posts.created DESC';
      

    # Run the query, store the results in the variable $posts
    $posts = DB::instance(DB_NAME)->select_rows($q);
    
    # Build the query to figure out what connections does this user already have? 
    # I.e. which posts are they following
    $q = "SELECT * 
        FROM users_posts
        WHERE user_id = ".$this->user->user_id;

	
	$join_connections = DB::instance(DB_NAME)->select_array($q, 'post_id_followed');
	

    # Pass data to the View
    $this->template->content->posts = $posts;
    
    $this->template->content->join_connections = $join_connections;

    # Render the View
    echo $this->template;
    
    }
    
    
    
    public function users() 
    {
    	$this->template->content = View::instance('v_posts_users');
    	
    	$q ='SELECT *
    		FROM users';
    	
    	$users = DB::instance(DB_NAME)->select_rows($q);
    	
    	$q = "SELECT *
    		FROM users_users
    		WHERE user_id = ".$this->user->user_id;
    		
    	$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
    	
    	$this-> template-> content-> users = $users;
    	$this->template->content->connections = $connections;
    	
    	echo  $this->template;
    }
    
    
    
    public function followers() 
    {
    	$this->template->content = View::instance('v_posts_followers');
    	
    	$q ='SELECT *
    		FROM users';
    	
    	$users = DB::instance(DB_NAME)->select_rows($q);
    		
    	$q = "SELECT *
    		FROM users_users
    		WHERE user_id_followed = ".$this->user->user_id;
    		
    	$f = "SELECT *
    		FROM users_users
    		WHERE user_id = ".$this->user->user_id;
    		
    	$connections = DB::instance(DB_NAME)->select_array($f, 'user_id_followed');


    	$following = DB::instance(DB_NAME)->select_array($q, 'user_id');
    	
    	$this->template->content->connections = $connections;
    	$this->template->content->following = $following;
    	$this-> template-> content-> users = $users;
    	
    	echo  $this->template;
    }    
    
        public function following() 
    {
    	$this->template->content = View::instance('v_posts_following');
    	
    	$q ='SELECT *
    		FROM users';
    	
    	$users = DB::instance(DB_NAME)->select_rows($q);
    	
    	$q = "SELECT *
    		FROM users_users
    		WHERE user_id = ".$this->user->user_id;
    		
    	$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');
    	
    	$this-> template-> content-> users = $users;
    	$this->template->content->connections = $connections;
    	
    	echo  $this->template;
    }
    
        
    public function follow($user_id_followed) {

    # Prepare the data array to be inserted
    $data = Array(
        "created" => Time::now(),
        "user_id" => $this->user->user_id,
        "user_id_followed" => $user_id_followed
        );

    # Do the insert
    DB::instance(DB_NAME)->insert('users_users', $data);

    # Send them back
    Router::redirect("/posts/users");

	}

	public function unfollow($user_id_followed) {

    # Delete this connection
    $where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;
    DB::instance(DB_NAME)->delete('users_users', $where_condition);

    # Send them back
    Router::redirect("/posts/users");

	}
	
	
	public function join($post_id_followed=NULL) {

    # Prepare the data array to be inserted
    echo $_POST['id'];
    $data = Array(
        "created" => Time::now(),
        "user_id" => $this->user->user_id,
        "post_id_followed" => $_POST['id']
        );
        
        
	#KIERAN
	     	
	$q = "SELECT users.email	 	
	     FROM posts
	     INNER JOIN users	 	
	     WHERE posts.post_id = ".$_POST['id']; 
		     
	$user_details = DB::instance(DB_NAME)->select_row($q);
	     
	$firstname=$this->user->first_name;
	
	$lastname=$this->user->last_name;
	
	$email=$this->user->email;
	
	# Do the insert
    DB::instance(DB_NAME)->insert('users_posts', $data);
	
	// send email to person
	$to = $user_details["email"] ;
	$subject = " $firstname $lastname wants to join you";
	
	$message = "Your friend $firstname $lastname has said he(she) is joining your event.";
	$from = "$email";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);    
	# Send them back
	#KIERAN
	
	//Router::redirect("/posts/index");
	}
	
	
	public function remove($post_id_followed = NULL) {

    # Delete this connection
    $where_condition = 'WHERE user_id = '.$this->user->user_id.' AND post_id_followed = '.$_POST['id'];
    DB::instance(DB_NAME)->delete('users_posts', $where_condition);
    
    # Send them back
//	Router::redirect("/posts/index");

	}
	
}


?>