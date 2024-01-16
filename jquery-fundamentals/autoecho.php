<?php
    if(!isset($_POST['val'])) return;
    /*Mili-seconds*/
    sleep(5);
    echo('You sent:' . $_POST['val']);
