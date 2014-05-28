<?php
	class userDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../userdb.sqlite');
	    }
	}
	class logDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../log.sqlite');
	    }
	}
	class configDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../config.sqlite');
	    }
	}
	class callsDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../calls.sqlite');
	    }
	}
?>