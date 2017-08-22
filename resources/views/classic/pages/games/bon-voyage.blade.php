@extends('classic.layouts.default')

@section('title')Ogniter - Games - Bon Voyage @stop
@section('description')Bon Voyage. Game based on The Oregon Travel Game @stop

@section('head')
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link rel="stylesheet" href="{{ $cdnHost.'games/css/bon-voyage.css?v=0.2' }}" />
    <style>
        .loading {
            height: 520px; margin-bottom: 50px;
            background: #000 url({{ $cdnHost.'img/ajax-loaders/ajax-loader-8.gif' }}) center center no-repeat;
        }
        .tbl-ships td .name { line-height: 12px; }
        .tbl-ships > tbody > tr > td { line-height: 10px; }
        .mid-buttons { line-height: 10px; }
    </style>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/">{{ $lang->trans('ogniter.og_home') }}</a><span class="divider">/</span></li>
        <li><a href="games/bon-voyage">Fleet, Bon Voyage!</a></li>
    </ul>
@endsection

@section('content')
    <div class="span12">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-plane"></i> Fleet, Bon Voyage!</h2>
            </div>
            <div class="box-content">
                <div class="alert alert-error hidden-desktop">
                    This game works better on resolutions of 1280px or wider.
                </div>
                <div class="loading">
                    <div id="root"></div>
                </div>
            </div>
        </div>
        <div class="box above-me">
            <div class="box-content">
                @include('classic.partials.disqus')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.bvConfig = {
            resourcePath: '{{$cdnHost }}ogame/skins/EpicBlue/images/',
            iconPath: '{{$cdnHost }}ogame/skins/EpicBlue/gebaeude/',
            shipData: {
                "10001" : {'id':"10001", 'name':'Planet', 'code':'planet', 'size':0,"position": null},
                "10002" : {'id':"10002", 'name':'Moon', 'code':'moon', 'size':0,"position": null},
                "10003" : {'id':"3", 'name':'Debris', 'code':'debris', 'size':0,"position": null},

                "109": {"id":"109", "name":"Military Tech", "code":"military_tech", "factor": 2, "metal": 800, "crystal": 200, "deuterium": 0},
                "110": {"id":"110", "name":"Shielding Tech", "code":"defense_tech", "factor": 2, "metal": 200, "crystal": 600, "deuterium": 0},
                "111": {"id":"111", "name":"Armor Tech", "code":"hull_tech", "factor": 2, "metal": 1000, "crystal": 0, "deuterium": 0},
                "115": {"id":"115","name": "Combustion Drive", "code":"combustion_drive_tech", "factor": 2, "metal": 400, "crystal": 0, "deuterium": 600},
                "117": {"id":"117","name": "Impulse Drive", "code":"impulse_drive_tech", "factor": 2, "metal": 2000, "crystal": 4000, "deuterium": 600},
                "118": {"id":"118","name": "Hyperspace Drive", "code":"hyperspace_drive_tech", "factor": 2, "metal": 10000, "crystal": 20000, "deuterium": 6000},
                "124": {"id":"124","name": "Astrophysics", "code":"astrophysics_tech", "factor": 1.75, "metal": 4000, "crystal": 8000, "deuterium": 4000},

                "202": {
                    "id":"202",
                    "code":"small_cargo",
                    "name": "Small Cargo",
                    "metal": 2000,
                    "crystal": 2000,
                    "deuterium": 0,
                    "energy": 0,
                    "factor": 1,
                    "capacity": 5000,
                    "attack": 5,
                    "defense": 10,
                    "hull": 4000,
                    "consumption": 10,
                    "speed": 5000,
                    "motor": 115,
                    "consumption2": 20,
                    "speed2": 10000,
                    "motor2": {"117": 4}
                },
                "203": {
                    "id":"203",
                    "code":"large_cargo",
                    "name": "Large Cargo",
                    "metal": 6000,
                    "crystal": 6000,
                    "deuterium": 0,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 50,
                    "speed": 7500,
                    "capacity": 25000,
                    "attack": 5,
                    "defense": 25,
                    "hull": 12000,
                    "motor": 115
                },
                "204": {
                    "id":"204",
                    "code":"light_fighter",
                    "name": "Light Fighter",
                    "metal": 3000,
                    "crystal": 1000,
                    "deuterium": 0,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 20,
                    "speed": 12500,
                    "capacity": 50,
                    "attack": 50,
                    "defense": 10,
                    "hull": 4000,
                    "motor": 115
                },
                "205": {
                    "id":"205",
                    "code": "heavy_fighter",
                    "name": "Heavy Fighter",
                    "metal": 6000,
                    "crystal": 4000,
                    "deuterium": 0,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 75,
                    "speed": 10000,
                    "capacity": 100,
                    "attack": 150,
                    "defense": 25,
                    "hull": 10000,
                    "motor": 117
                },
                "206": {
                    "id":"206",
                    "code":"cruiser",
                    "name": "Cruiser",
                    "metal": 20000,
                    "crystal": 7000,
                    "deuterium": 2000,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 300,
                    "speed": 15000,
                    "capacity": 800,
                    "attack": 400,
                    "defense": 50,
                    "hull": 27000,
                    "motor": 117
                },
                "207": {
                    "id":"207",
                    "code":"battle_ship",
                    "name": "Battleship",
                    "metal": 45000,
                    "crystal": 15000,
                    "deuterium": 0,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 500,
                    "speed": 10000,
                    "capacity": 1500,
                    "attack": 1000,
                    "defense": 200,
                    "hull": 60000,
                    "motor": 118
                },
                "208": {
                    "id":"208",
                    "code":"colony_ship",
                    "name": "Colony Ship",
                    "metal": 10000,
                    "crystal": 20000,
                    "deuterium": 10000,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 1000,
                    "speed": 2500,
                    "capacity": 7500,
                    "attack": 50,
                    "defense": 100,
                    "hull": 30000,
                    "motor": 117
                },
                "209": {
                    "id":"209",
                    "code":"recycler",
                    "name": "Recycler",
                    "metal": 10000,
                    "crystal": 6000,
                    "deuterium": 2000,
                    "energy": 0,
                    "factor": 1,
                    "capacity": 20000,
                    "attack": 1,
                    "defense": 10,
                    "hull": 16000,
                    "consumption": 300,
                    "speed": 2000,
                    "motor": 115,
                    "consumption2": 300,
                    "speed2": 4000,
                    "motor2": {"117": 16},
                    "consumption3": 300,
                    "speed3": 6000,
                    "motor3": {"118": 14}
                },
                "210": {
                    "id":"210",
                    "code":"esp_probe",
                    "name": "Espionage Probe",
                    "metal": 0,
                    "crystal": 1000,
                    "deuterium": 0,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 1,
                    "speed": 100000000,
                    "capacity": 5,
                    "attack": 0.01,
                    "defense": 0.01,
                    "hull": 1000,
                    "motor": 115
                },
                "211": {
                    "id":"211",
                    "code":"bomber_ship",
                    "name": "Bomber",
                    "metal": 50000,
                    "crystal": 25000,
                    "deuterium": 15000,
                    "energy": 0,
                    "factor": 1,
                    "capacity": 500,
                    "attack": 1000,
                    "defense": 500,
                    "hull": 75000,
                    "consumption": 1000,
                    "speed": 4000,
                    "motor": 117,
                    "consumption2": 1000,
                    "speed2": 5000,
                    "motor2": {"118": 7}
                },
                "213": {
                    "id":"213",
                    "code":"destroyer",
                    "name": "Destroyer",
                    "metal": 60000,
                    "crystal": 50000,
                    "deuterium": 15000,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 1000,
                    "speed": 5000,
                    "capacity": 2000,
                    "attack": 2000,
                    "defense": 500,
                    "hull": 110000,
                    "motor": 118
                },
                "214": {
                    "id":"214",
                    "code":"death_star",
                    "name": "Death Star",
                    "metal": 5000000,
                    "crystal": 4000000,
                    "deuterium": 1000000,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 1,
                    "speed": 100,
                    "capacity": 1000000,
                    "attack": 200000,
                    "defense": 50000,
                    "hull": 9000000,
                    "motor": 118
                },
                "215": {
                    "id":"215",
                    "code":"battle_cruiser",
                    "name": "Battle Cruiser",
                    "metal": 30000,
                    "crystal": 40000,
                    "deuterium": 15000,
                    "energy": 0,
                    "factor": 1,
                    "consumption": 250,
                    "speed": 10000,
                    "capacity": 750,
                    "attack": 700,
                    "defense": 400,
                    "hull": 70000,
                    "motor": 118
                },
                "401": {
                    "name": "Rocket launcher",
                    'metal': 2000,
                    'crystal': 0,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 80,
                    'defense': 20,
                    'hull': 2000
                },
                "402": {
                    "name": "Light Laser",
                    'metal': 1500,
                    'crystal': 500,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 100,
                    'defense': 25,
                    'hull': 2000
                },
                "403": {
                    "name": "Heavy Laser",
                    'metal': 6000,
                    'crystal': 2000,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 250,
                    'defense': 100,
                    'hull': 8000
                },
                "404": {
                    "name": "Gauss Cannon",
                    'metal': 20000,
                    'crystal': 15000,
                    'deuterium': 2000,
                    'energy': 0,
                    'factor': 1,
                    'attack': 1100,
                    'defense': 200,
                    'hull': 35000
                },
                "405": {
                    "name": "Ion Cannon",
                    'metal': 2000,
                    'crystal': 6000,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 150,
                    'defense': 500,
                    'hull': 8000
                },
                "406": {
                    "name": "Plasma Turret",
                    'metal': 50000,
                    'crystal': 50000,
                    'deuterium': 30000,
                    'energy': 0,
                    'factor': 1,
                    'attack': 3000,
                    'defense': 300,
                    'hull': 100000
                },
                "407": {
                    "name": "Small Shield Dome",
                    'metal': 10000,
                    'crystal': 10000,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 1,
                    'defense': 2000,
                    'hull': 20000
                },
                "408": {
                    "name": "Large Shield Dome",
                    'metal': 50000,
                    'crystal': 50000,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 1,
                    'defense': 10000,
                    'hull': 100000
                },
                "502": {
                    "name": "Anti-Ballistic Missiles",
                    'metal': 8000,
                    'crystal': 2000,
                    'deuterium': 0,
                    'energy': 0,
                    'factor': 1,
                    'attack': 1,
                    'defense': 1,
                    'hull': 8000
                },
                "503": {
                    "name": "Interplanetary Missiles",
                    'metal': 12500,
                    'crystal': 2500,
                    'deuterium': 10000,
                    'energy': 0,
                    'factor': 1,
                    'attack': 12000,
                    'defense': 1,
                    'hull': 15000
                }
            },
            rapidFire: {
                "202": {"210":5,"212":5},
                "203": {"210":5,"212":5},
                "204": {"210":5,"212":5},
                "205": {"210":5,"212":5,"203":3},
                "206": {"210":5,"212":5,"204":6,"401":10},
                "207": {"210":5,"212":5},
                "208": {"210":5,"212":5},
                "209": {"210":5,"212":5},
                "210": {},
                "211": {"210":5,"212":5,"401":120,"402":120,"403":10,"405":10},
                "212": {},
                "213": {"210":5,"212":5,"215":3,"402":10},
                "214": {"210":1250,"212":1250,"202":250,"203":250,"204":200,"205":100,"206":33,"207":30,"208":250,"209":250,"211":25,"213":5,"215":15,"401":200,"402":200,"403":100,"404":50,"405":100},
                "215": {"202":3,"203":3,"206":5,"207":8}
            }
        };
    </script>
    <script type="text/javascript" src="{{ $cdnHost.'games/js/bon-voyage/bundle.js?v=0.2.5' }}"></script>
@endsection