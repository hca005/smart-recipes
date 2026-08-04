<?php
require_once '../../includes/bootstrap.php';

session_unset();
session_destroy();

redirect_to('/smart-recipes/frontend/pages/home.php');