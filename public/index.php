<?php
session_start();
require_once "../app/core/initial.php";

$App = new App();

$App->loadController();
