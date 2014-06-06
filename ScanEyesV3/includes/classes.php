<?php
	class callsDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/calls.sqlite');
	    }
	}
	class configDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/config.sqlite');
	    }
	}
	class logDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/log.sqlite');
	    }
	}
	class talkgroupsDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/talkgroups.sqlite');
	    }
	}
	class userDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/userdb.sqlite');
	    }
	}
?>