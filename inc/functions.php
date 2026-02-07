<?php

// inc/functions.php
require_once DIR . '/config.php';

//récupération des horaires
function get_horaires_mongo():mixed{
    global $horaires;
    return $horaires->find()->toArray();
}

?>