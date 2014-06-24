<?php
## new method
$callsClass = new PDO('sqlite:../database/calls.sqlite');
$configClass = new PDO('sqlite:../database/config.sqlite');
$logClass = new PDO('sqlite:../database/log.sqlite');
$talkgroupsClass = new PDO('sqlite:../database/talkgroups.sqlite');
$userdbClass = new PDO('sqlite:../database/userdb.sqlite');
$playlistsClass = new PDO('sqlite:../database/playlists.sqlite');
## old method
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
	class playlistDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/playlists.sqlite'); //Done
	    }
	}
?>