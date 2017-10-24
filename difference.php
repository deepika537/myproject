<?php
#http://code.stephenmorley.org/php/diff-implementation/
// include the Diff class
require_once './class.Diff.php';

// output the result of comparing two files as a table
$diff=Diff::compareFiles('first.txt', 'second.txt');
$string1='';
$string2='';
foreach ($diff as $line){
      // extend the string with the line
      switch ($line[1]){
        case 1   : $string1 .= $line[0];break;
        case 2   : $string2 .= $line[0];break;
      }
}
echo 'deleted - <br> <div style="background-color: #faa;"><strike>'.$string1.'</strike></div>';
echo 'Added + <br> <div style="background-color: #dfd">'.$string2.'</div>';
?>
