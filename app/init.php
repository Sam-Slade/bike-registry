<?php

// Require and declare class instance
require_once 'core/consts.php';
$constant = new Constant;

require_once $constant->APP;
require_once $constant->CONTROLLER;
require_once $constant->MODEL;
Model::dbInit();
