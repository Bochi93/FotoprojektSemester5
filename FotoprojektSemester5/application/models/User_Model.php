<?php 
   Class User_Model extends CI_Model {
	
      Public function __construct() { 
         parent::__construct(); 
      } 
      
      Public function getAllUsers(){

      	$query = $this->db->get("user");
      	return $query->result();
      }
		
   } 
?>