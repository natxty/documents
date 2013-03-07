<?php
/**
 * Coded in JetBrains PhpStorm
 * User: natxty
 * Date: 10/5/11
 * Time: 12:09 PM
 */


?>

<script>
    var x1=100, y1=50, x2=250, y2=70;
    var X=x1-x2;
    var Y=y2-y1;
    var Z=Math.round(Math.sqrt(Math.pow(X,2)+Math.pow(Y,2)));//the distance - rounded - in pixels
    var r=Math.atan2(Y,X);//angle in radians (Cartesian system)
    var d=r*180/Math.PI//angle in degrees
    d<0?d+=Math.PI*2:null;//correction for "negative" quadrants

    document.write(d);
</script>
