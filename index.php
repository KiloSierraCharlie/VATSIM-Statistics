<?php

echo "<table>";
echo "<tr>";
echo "<th>Certificate</th>";
echo "<th>Full Name</th>";
echo "<th>Counted Time</th>";
echo "</tr>";
$results = json_decode( file_get_contents( date( "M-Y" ) . "-stats.json" ), true );
usort($results, function($a, $b) {
    return $b['accountedTime'] - $a['accountedTime'];
});
foreach( $results as $member ){
    
    if( $member["accountedTime"] == 0 ){ continue; }
    echo "<tr>";
    echo "<td>" . $member["cid"] . "</td>";
    echo "<td>" . $member["realname"] . "</td>";
    echo "<td>" . gmdate( "H:i:s", $member["accountedTime"] ) . "</td>";
    echo "</tr>";
    
}
echo "</table>";