<?php
/*
function getCharacter(int $type):string{
  if($type===0){
    $charsRange = range('A', 'Z');
  }
  else{
    $charsRange = range(0, 9);
  }
  $pos=rand(0,count($charsRange)-1);
  return (string) $charsRange[$pos];
}

$robotName='';
$lettersCount=2;
$numbersCount=3;

while($lettersCount>0){
  $lettersCount--;
  $robotName.=getCharacter(0);
}
while($numbersCount>0){
  $numbersCount--;
  $robotName.=getCharacter(1);
}
*/
declare(strict_types=1);

function getRobotName(){
  $lettersRange = range('A', 'Z');
  shuffle($lettersRange);
  $numbersRange = (string) mt_rand(100,999);

  return join('',array_slice($lettersRange,0,2)).  $numbersRange;


}
var_dump(getRobotName());
