<?php

if(!defined('PLANET')) define( "PLANET", 1 );
if(!defined('MOON')) define( "MOON", 2 );
if(!defined('DEBRIS_FACTOR')) define("DEBRIS_FACTOR", 0.3 );
if(!defined('REGEN_DEF_FACTOR')) define("REGEN_DEF_FACTOR", 0.3 );

//
if(!defined('MIN_MOON_PERCENT')) define( "MIN_MOON_PERCENT", 1 );
if(!defined('MAX_MOON_PERCENT')) define( "MAX_MOON_PERCENT", 20 );
if(!defined('DEBRIS_PERCENT')) define( "DEBRIS_PERCENT", 100000 );
if(!defined('MAX_USERS_PER_SQUAD')) define( "MAX_USERS_PER_SQUAD", 5 ); //No usado.
if(!defined('MAX_BATTLE_ROUNDS')) define( "MAX_BATTLE_ROUNDS", 7 );

if(!defined('IN_BATTLE_ROUND')) define( "IN_BATTLE_ROUND", 0);
if(!defined('ATTACKERS_LOST')) define( "ATTACKERS_LOST", 4);
if(!defined('DEFENDERS_LOST')) define( "DEFENDERS_LOST", 8);
if(!defined('BOTH_SIDES_LOST')) define( "BOTH_SIDES_LOST", 12);
if(!defined('BATTLE_BLOCKED')) define( "BATTLE_BLOCKED", 2);
if(!defined('DRAW_GAME')) define( "DRAW_GAME", 3);

//OPCIONES PARA $vars->flags
if(!defined('DEFENSE_TO_DEBRIS')) define( "DEFENSE_TO_DEBRIS", 2);
if(!defined('DISABLE_RAPIDFIRE')) define( "DISABLE_RAPIDFIRE", 4);

if(!defined('ATTACKER')) define('ATTACKER',10);
if(!defined('DEFENDER')) define('DEFENDER',11);

//Tipo de batalla (no usado)
/*
define("NORMAL", 1);
define("SAC", 2);
define("DESTROY", 3);
*/

if(!defined('SMALL_CARGO')) define('SMALL_CARGO',202);
if(!defined('LARGE_CARGO')) define('LARGE_CARGO',203);
if(!defined('LIGHT_FIGHTER')) define('LIGHT_FIGHTER',204);
if(!defined('HEAVY_FIGHTER')) define('HEAVY_FIGHTER',205);
if(!defined('CRUISER')) define('CRUISER',206);
if(!defined('BATTLE_SHIP')) define('BATTLE_SHIP',207);
if(!defined('COLONY_SHIP')) define('COLONY_SHIP',208);
if(!defined('RECYCLER')) define('RECYCLER',209);
if(!defined('ESP_PROBE')) define('ESP_PROBE',210);
if(!defined('BOMBER_SHIP')) define('BOMBER_SHIP',211);
if(!defined('DESTROYER')) define('DESTROYER',213);
if(!defined('DEATH_STAR')) define('DEATH_STAR',214);
if(!defined('BATTLE_CRUISER')) define('BATTLE_CRUISER',215);
if(!defined('ROCKET_LAUNCHER')) define('ROCKET_LAUNCHER',401);
if(!defined('LIGHT_LASER')) define('LIGHT_LASER',402);
if(!defined('HEAVY_LASER')) define('HEAVY_LASER',403);
if(!defined('GAUSS_CANON')) define('GAUSS_CANON',404);
if(!defined('ION_CANNON')) define('ION_CANNON',405);
if(!defined('PLASMA_TURRET')) define('PLASMA_TURRET',406);
if(!defined('SMALL_SHIELD_DOME')) define('SMALL_SHIELD_DOME',407);
if(!defined('LARGE_SHIELD_DOME')) define('LARGE_SHIELD_DOME',408);

if(!defined('MILITARY_TECH')) define('MILITARY_TECH',109);
if(!defined('DEFENSE_TECH')) define('DEFENSE_TECH',110);
if(!defined('HULL_TECH')) define('HULL_TECH', 111);


return [
    'pricelist' => [
        //npc
        202 => array('metal'=>2000,'crystal'=>2000,'deuterium'=>0,'energy'=>0,'factor'=>1,'capacity'=>5000,
            'attack'=>5, 'defense'=>10, 'hull'=>4000 ,
            'consumption'=>10, 'speed'=>5000, 'motor'=>115,
            'consumption2'=>20, 'speed2'=>10000, 'motor2'=>array(117=>4)
        ),
        //ngc
        203 => array('metal'=>6000,'crystal'=>6000,'deuterium'=>0,'energy'=>0,'factor'=>1,
            'consumption'=>50,'speed'=>7500,'capacity'=>25000, 'attack'=>5, 'defense'=>25, 'hull'=>12000 , 'motor'=>115 ),
        //cazador ligero
        204 => array('metal'=>3000,'crystal'=>1000,'deuterium'=>0,'energy'=>0,'factor'=>1,
            'consumption'=>20,'speed'=>12500,'capacity'=>50, 'attack'=>50, 'defense'=>10, 'hull'=>4000 , 'motor'=>115 ),
        //cazador pesado
        205 => array('metal'=>6000,'crystal'=>4000,'deuterium'=>0,'energy'=>0,'factor'=>1,
            'consumption'=>75,'speed'=>10000,'capacity'=>100, 'attack'=>150, 'defense'=>25, 'hull'=>10000 , 'motor'=>117 ),
        //crucero
        206 => array('metal'=>20000,'crystal'=>7000,'deuterium'=>2000,'energy'=>0,'factor'=>1,
            'consumption'=>300,'speed'=>15000,'capacity'=>800, 'attack'=>400, 'defense'=>50, 'hull'=>27000 , 'motor'=>117 ),
        //Nave de batalla
        207 => array('metal'=>45000,'crystal'=>15000,'deuterium'=>0,'energy'=>0,'factor'=>1,
            'consumption'=>500,'speed'=>10000,'capacity'=>1500, 'attack'=>1000, 'defense'=>200, 'hull'=>60000 , 'motor'=>118),
        //colonizador
        208 => array('metal'=>10000,'crystal'=>20000,'deuterium'=>10000,'energy'=>0,'factor'=>1,
            'consumption'=>1000,'speed'=>2500,'capacity'=>7500, 'attack'=>50, 'defense'=>100, 'hull'=>30000 , 'motor'=>117),
        //reciclador
        209 => array(
            'metal'=>10000,'crystal'=>6000,'deuterium'=>2000,'energy'=>0,'factor'=>1,
            'capacity'=>20000, 'attack'=>1, 'defense'=>10, 'hull'=>16000 ,
            'consumption'=>300,'speed'=>2000, 'motor'=>115,
            'consumption2'=>300,'speed2'=>4000, 'motor2'=>array(117=>16),
            'consumption3'=>300,'speed3'=>6000, 'motor3'=>array(118=>14)
        ),
        210 => array('metal'=>0,'crystal'=>1000,'deuterium'=>0,'energy'=>0,'factor'=>1,
            'consumption'=>1,'speed'=>100000000,'capacity'=>5, 'attack'=>0.01, 'defense'=>0.01, 'hull'=>1000 , 'motor'=>115 ),
        //Bombardero
        211 => array('metal'=>50000,'crystal'=>25000,'deuterium'=>15000,'energy'=>0,'factor'=>1,
            'capacity'=>500, 'attack'=>1000, 'defense'=>500, 'hull'=>75000,
            'consumption'=>1000,'speed'=>4000, 'motor'=>117,
            'consumption2'=>1000,'speed2'=>5000, 'motor2'=>array(118=>7)
        ),
        212 => array('metal'=>0,'crystal'=>2000,'deuterium'=>500,'energy'=>0,'factor'=>1,
            'consumption'=> 0, 'speed'=>0, 'capacity'=>0, 'attack'=>1, 'defense'=>1, 'hull'=>2000 , 'motor'=> -1),
        213 => array('metal'=>60000,'crystal'=>50000,'deuterium'=>15000,'energy'=>0,'factor'=>1,
            'consumption'=>1000,'speed'=>5000,'capacity'=>2000, 'attack'=>2000, 'defense'=>500, 'hull'=>110000 , 'motor'=>118),
        214 => array('metal'=>5000000,'crystal'=>4000000,'deuterium'=>1000000,'energy'=>0,'factor'=>1,
            'consumption'=>1,'speed'=>100,'capacity'=>1000000, 'attack'=>200000, 'defense'=>50000, 'hull'=>9000000 , 'motor'=>118),
        215 => array('metal'=>30000,'crystal'=>40000,'deuterium'=>15000,'energy'=>0,'factor'=>1,
            'consumption'=>250,'speed'=>10000,'capacity'=>750, 'attack'=>700, 'defense'=>400, 'hull'=>70000 , 'motor'=>118 ),
        //Sistemas de defensa
        401 => array('metal'=>2000,'crystal'=>0,'deuterium'=>0,/*'energy'=>0,'factor'=>1,*/
            'attack'=>80, 'defense'=>20, 'hull'=>2000),
        402 => array('metal'=>1500,'crystal'=>500,'deuterium'=>0,/*'energy'=>0,'factor'=>1,*/
            'attack'=>100, 'defense'=>25, 'hull'=>2000),
        403 => array('metal'=>6000,'crystal'=>2000,'deuterium'=>0,/*'energy'=>0,'factor'=>1,*/
            'attack'=>250, 'defense'=>100, 'hull'=>8000),
        404 => array('metal'=>20000,'crystal'=>15000,'deuterium'=>2000,/*'energy'=>0,'factor'=>1,*/
            'attack'=>1100, 'defense'=>200, 'hull'=>35000),
        405 => array('metal'=>2000,'crystal'=>6000,'deuterium'=>0,/*'energy'=>0,'factor'=>1, */
            'attack'=>150, 'defense'=>500, 'hull'=>8000),
        406 => array('metal'=>50000,'crystal'=>50000,'deuterium'=>30000,/*'energy'=>0,'factor'=>1,*/
            'attack'=>3000, 'defense'=>300, 'hull'=>100000),
        407 => array('metal'=>10000,'crystal'=>10000,'deuterium'=>0,/*'energy'=>0,'factor'=>1, */
            'attack'=>1, 'defense'=>2000, 'hull'=>20000),
        408 => array('metal'=>50000,'crystal'=>50000,'deuterium'=>0,/*'energy'=>0,'factor'=>1, */
            'attack'=>1, 'defense'=>10000, 'hull'=>100000),
        502 => array('metal'=>8000,'crystal'=>2000,'deuterium'=>0,/*'energy'=>0,'factor'=>1, */
            'attack'=>1, 'defense'=>1, 'hull'=>8000),
        503 => array('metal'=>12500,'crystal'=>2500,'deuterium'=>10000,/*'energy'=>0,'factor'=>1, */
            'attack'=>12000, 'defense'=>1, 'hull'=>15000)
    ],

    'tech_ids' => [
        '1' => 'metal_mine',
        '2' => 'crystal_mine',
        '3' => 'deuterium_synthesizer',
        '4' => 'solar_plant',
        '12' => 'fusion_reactor',
        '14' => 'robotics_factory',
        '15' => 'nanite_factory',
        '21' => 'shipyard',
        '22' => 'metal_storage',
        '23' => 'crystal_storage',
        '24' => 'deuterium_tank',
        '25' => 'shielded_metal_den',
        '26' => 'underground_metal_den',
        '27' => 'seabed_deuterium_den',
        '31' => 'research_lab',
        '33' => 'terraformer',
        '34' => 'alliance_depot',
        '41' => 'lunar_base',
        '42' => 'sensor_phalanx',
        '43' => 'jump_gate',
        '44' => 'missile_silo',
        '106' => 'espionage_tech',
        '108' => 'computer_tech',
        '109' => 'military_tech',
        '110' => 'shielding_tech',
        '111' => 'armor_tech',
        '113' => 'energy_tech',
        '114' => 'hyperspace_tech',
        '115' => 'combustion_drive',
        '117' => 'impulse_drive',
        '118' => 'hyperspace_drive',
        '120' => 'laser_tech',
        '121' => 'ion_tech',
        '122' => 'plasma_tech',
        '123' => 'intergalactic_research_network',
        '124' => 'astrophysics',
        '199' => 'graviton_tech',
        '202' => 'small_cargo',
        '203' => 'large_cargo',
        '204' => 'light_fighter',
        '205' => 'heavy_fighter',
        '206' => 'cruiser',
        '207' => 'battle_ship',
        '208' => 'colony_ship',
        '209' => 'recycler',
        '210' => 'esp_probe',
        '211' => 'bomber_ship',
        '212' => 'solar_satellite',
        '213' => 'destroyer',
        '214' => 'death_star',
        '215' => 'battle_cruiser',
        '401' => 'rocket_launcher',
        '402' => 'light_laser',
        '403' => 'heavy_laser',
        '404' => 'gauss_cannon',
        '405' => 'ion_cannon',
        '406' => 'plasma_turret',
        '407' => 'small_shield_dome',
        '408' => 'large_shield_dome',
        '502' => 'antiballistic_missile',
        '503' => 'interplanetary_missile'
    ],
    'building_list' => ['1','2','3','4','12','14','15','21','22','23','24','25','26','27','31','33','34','41','42','43','44'],
    'research_list' => ['106','108','109','110','111','113','114','115','117','118','120','121','122','123','124','199'],
    'ship_list' => ['202','203','204','205','206','207','208','209','210','211','212','213','214','215'],
    'battle_defense_list' => ['401','402','403','404','405','406','407','408'],
    'defense_list' => ['401','402','403','404','405','406','407','408','502','503'],
    'mission_ids' => [
        '1' => 'attack',
        '2' => 'acs_attack',
        '3' => 'transport',
        '4' => 'deployment',
        '5' => 'acs_defend',
        '6' => 'espionage',
        '7' => 'colonisation',
        '8' => 'harvest',
        '9' => 'moon_destruction',
        '15' => 'expedition',
    ],

    'battle_techs' => [109,110,111],
    'motors' => [115,117,118],

    'rapidfire' => [
        '202' => array(210=>5,212=>5),
        '203' => array(210=>5,212=>5),
        '204' => array(210=>5,212=>5),
        '205' => array(210=>5,212=>5,203=>3),
        '206' => array(210=>5,212=>5,204=>6,401=>10),
        '207' => array(210=>5,212=>5),
        '208' => array(210=>5,212=>5),
        '209' => array(210=>5,212=>5),
        '210' => NULL,
        '211' => array(210=>5,212=>5,401=>120,402=>120,403=>10,405=>10),
        '212' => NULL,
        '213' => array(210=>5,212=>5,215=>3,402=>10),
        '214' => array(210=>1250,212=>1250,202=>250,203=>250,204=>200,205=>100,206=>33,207=>30,
            208=>250,209=>250,211=>25,213=>5,215=>15,401=>200,402=>200,403=>100,404=>50,405=>100),
        '215' => array(202=>3,203=>3,206=>5,207=>8),
    ]
];