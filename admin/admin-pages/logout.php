<?php
  require_once("../function/function.php");

  session_start(); 

  session_unset();

  session_destroy();

  redirect("../login/admin-login.php");

