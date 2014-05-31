<?php
	class userDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/userdb.sqlite');
	    }
	}
	class logDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/log.sqlite');
	    }
	}
	class configDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/config.sqlite');
	    }
	}
	class callsDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../database/calls.sqlite');
	    }
	}
?>