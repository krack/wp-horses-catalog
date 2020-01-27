<?php
function havePedigree($horse){
    return $horse->father != null || $horse->mother != null ;
}


function haveStrongPoints($horse){
    return (count($horse->strongPoints) >= 1 && $horse->strongPoints[0] != "");
}

function haveMotherNotes($horse){
    return (count($horse->mothersNotes) > 0);
}

