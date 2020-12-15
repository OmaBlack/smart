<?php
	
//lazy CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
Header("Access-Control-Allow-Credentials: true");
session_start();