<?php

// inc/functions.php
require_once __DIR__ . '/config.php';

//récupération des horaires
function get_horaires_mongo():mixed{
    global $horaires;
    return $horaires->find()->toArray();
}

?>