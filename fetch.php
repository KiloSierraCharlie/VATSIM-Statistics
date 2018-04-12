<?php
require( "DataHandler/DataHandler.php" );
$DH = new DataHandler();

$stats = array();
if( file_exists( date("M-Y") . "-stats.json" ) ){ $stats = json_decode( file_get_contents( date("M-Y") . "-stats.json" ), true ); }
//(CYVR|CYYJ|CYWH|CYCD|CYAZ|CYBL|CYQQ|CYPW|CYZT|CYBD|CYZP|CYPR|CZST|CYXT|CYYD|CYPZ|CYZY|CYXS|CYXS|CYQZ|CYWL|CYCP|CYKA|CYRV|CYXC|CYCG|CZGF|CYYF|CYLW|CYYF|CYDC|CYHE|CYXX|CZVR)
foreach( $DH->searchFor( "getAirTraffic", "/.+/", "callsign" ) as $client ){ 
    
    if( !array_key_exists( $client->cid, $stats ) ){
        
        $stats[ $client->cid ] = array(
            "cid" => $client->cid,
            "realname" => $client->realname,
            "accountedTime" => 0,
            "positions" => array(),
        );
        
    }
    
    if( !array_key_exists( $client->callsign, $stats[ $client->cid ][ "positions" ] ) ){
        
        $stats[ $client->cid ][ "positions" ][ $client->callsign ] = array();
        
    }
    
    if( empty( $stats[ $client->cid ][ "positions" ][ $client->callsign ] ) or
        end( $stats[ $client->cid ][ "positions" ][ $client->callsign ] )[ "logoff_time" ] != false ){
                                
        $year = substr( $client->time_logon, 0, 4 );
        $month = substr( $client->time_logon, 4, 2 );
        $day = substr( $client->time_logon, 6, 2 );
        $time = substr( $client->time_logon, 8, 2 ) . ":" . substr( $client->time_logon, 10, 2 ) . ":" . substr( $client->time_logon, 12, 2 );
        
        $logonTime = strtotime( "$day-$month-$year $time" );
 
        array_push( $stats[ $client->cid ][ "positions" ][ $client->callsign ], 
            array(
                "logon_time" => $logonTime,
                "logoff_time" => false,
            )
        ); 
        
    }
    
}

foreach( $stats as $cid=>$member ){
    
    foreach( $member["positions"] as $callsign=>$position ){
        
        foreach( $position as $connectionNumber=>$positionTiming ){
            
            if( $positionTiming[ "logoff_time" ] == false ){
                
                $results = $DH->searchFor( "getClients", "/$callsign/", "callsign" );
                
                foreach( $results as $connectedMember ){ if( $connectedMember->cid == $cid ){ continue 2; } }
                
                $stats[ $cid ][ "positions" ][ $callsign ][ $connectionNumber ][ "logoff_time" ] = $DH->lastUpdate;
                
                if( !preg_match( "/_ATIS/", $callsign ) ){
                    
                    $stats[ $cid ][ "accountedTime" ] += ( $DH->lastUpdate - $stats[ $cid ][ "positions" ][ $callsign ][ $connectionNumber ][ "logon_time" ] );
                
                }
                
            }
            
        }
        
    }
    
}

file_put_contents( date("M-Y") . "-stats.json", json_encode( $stats ) );