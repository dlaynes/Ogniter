<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - Online Stats for Ogame</title>
    <meta name="description" content="@yield('description'). Information about the Fantasy Browser Game Ogame.">
    <meta name="author" content="Donato C. Laynes Gonzales">
    <base href="{{ $baseUrl }}">
    <script>
        window.Ogniter = {
            BASE_URL: "{{ $baseUrl }}/",
            CDN_HOST: "{{ $cdnHost }}"
        };
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title')>"/>
    <meta property="og:url" content="{{ $baseUrl.$currentPath }}"/>
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image" content="{{ $cdnHost }}img/home-ogniter.jpg"/>
    <meta property="og:site_name" content="Ogniter"/>
    <meta property="fb:app_id" content="453851131318585"/>

    <link rel="canonical" href="{{ $baseScheme }}://en.{{ $baseDomain.$request->path() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link id="bs-css" href="{{ $cdnHost }}css/bootstrap-{{ $currentThemeId }}.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Shojumaru" />
    <link href="{{ $cdnHost }}css/combined20160701.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="{{ $cdnHost }}img/favicon.ico">
    @yield('head')
    @if ($environment=='production')
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-34506622-1']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
                //ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ $cdnHost }}js/jquery-1.7.2.min.js"><\/script>')</script>
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-1243866001028722",
                enable_page_level_ads: true
            });
        </script>
    @elseif($environment=='staging')
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-76792716-1', 'auto');
            ga('send', 'pageview');
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="{{ $cdnHost }}>js/jquery-1.7.2.min.js"><\/script>')</script>
    @else
        <script src="{{ $cdnHost }}js/jquery-1.7.2.min.js"></script>
    @endif
</head>
<body>

<div id="fb-root"></div>
<div class="container">
    <div class="navbar">
        <div class="navbar-inner">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="pull-left">
                <a href="{{ $baseUrl }}"><img src="{{ $cdnHost }}img/ogniter-logo.png" alt="Ogniter" title="Ogniter" /></a> &nbsp;
            </div>
            <?php $path = $request->path(); $segment = $request->segment(0) ?>
            @if( ($segment == 'site'
                || $segment == 'terms-of-use'
                || $segment == 'privacy-policy'
                || strpos($path, 'evolution')!==FALSE
                || strpos($path, 'polls')!==FALSE
                || strpos($path, 'poll')!==FALSE) || $segment != 'site' )
                <div class="btn-group pull-left" >
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="hidden-phone"> {!! !empty($currentCountry)
                        ? '<i class="flag flag-'.e($currentCountry->flag).'"></i> '.e($currentCountry->domain)
                        : $lang->trans('ogniter.og_pick_a_domain') !!}</span>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" id="domains">
                        @foreach($countries as $dom)
                            <li><a href="{{ $dom->language }}">
                                    <i class="flag flag-{{ $dom->flag }}"></i> {{ $dom->domain }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @if(isset($currentCountry) && isset($universes)&& count($universes) )
                    <div class="btn-group pull-left" >
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="hidden-phone"> {{ isset($currentUniverse) ?
                          $currentUniverse->local_name : $lang->trans('ogniter.og_choose_a_server') }} </span><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" id="servers">
                            @foreach($universes as $universe)
                                <li><a href="{{ $currentCountry->language.'/'.$universe->id.'/galaxy' }}"><span{!! $universe->api_enabled ? '' : ' class="text-warning"' !!}>{{ $universe->local_name }}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif

            <div class="btn-group pull-right" >
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-flag"></i> <span class="hidden-phone"> {{ $languages[$currentLanguageId]['desc'] }} </span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach( $languages as $key => $value )
                        <li><a href="{{ $baseScheme.'://'.$key.'.'.$baseDomain.$currentPath }}">
                                <i class="icon-blank
                            @if ($currentLanguageId==$key) icon-ok
                            @endif"></i> {{ $value['desc'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-group pull-right" >
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-wrench"></i><span class="hidden-phone"></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="site/flight_times"><i class="icon-time"></i> {{ $lang->trans('ogniter.flight_time_calculator')}} </a></li>
                    <?php /*<li><a href="site/battle"><i class="icon-time"></i> Battle simulator</a></li>*/ ?>
                </ul>
            </div>
            <div class="btn-group pull-right theme-container" >
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-tint"></i><span class="hidden-phone">
                        {{ $lang->trans('ogniter.theme').': '.$themes[$currentThemeId]['desc'] }}
                    </span><span class="caret"></span>
                </a>
                <ul class="dropdown-menu" id="themes">
                    @foreach($themes as $k => $th)
                        <li><a href="site/theme/{{ $k.'/'.\App\Ogniter\Tools\Strings\Encrypt::urlBase64Encode($currentPath) }}" rel="nofollow">
                                <i class="icon-blank
                            @if($currentThemeId==$th['name']) icon-ok
                            @endif"></i> {{ $th['desc'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-group pull-right" >
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-gift"></i> <span class="hidden-phone"> Games </span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="games/bon-voyage">
                                <i class="icon-blank icon-plane"></i> Bon Voyage</a></li>
                </ul>
            </div>

            <div class="clearfix"></div>
            <div class="pull-left">
                <?php /*
					<ul class="nav">
						<li><a href="discussions"><i class="icon-pencil"></i> {{ $lang->trans('discussions') }}?></a>
					</ul> */ ?>
            </div>
            <div class="pull-right">
                &nbsp;
                @if($environment=='production')
                    <script id="_waut83">var _wau = _wau || []; _wau.push(["small", "6uc7x1fficvk", "t83"]);
                        (function() {var s=document.createElement("script"); s.async=true;
                            s.src="//widgets.amung.us/small.js";
                            document.getElementsByTagName("head")[0].appendChild(s);
                        })();</script>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="container">
    <noscript>
        <div class="row">
            <div class="alert alert-block span12">
                <p>{{ $lang->trans('ogniter.pls_enable_javascript') }}</p>
            </div>
        </div>
    </noscript>
    <div class="row">
        <div class="span2 main-menu-span">
            <div class="well nav-collapse sidebar-nav">
                <ul class="nav nav-tabs nav-stacked main-menu">
                    <li><a href="site/faq"><i class="icon-exclamation-sign"></i> {{ $lang->trans('ogniter.faq_support') }}</a></li>
                    <li><a href="site/recommended"><i class="icon-share"></i> {{ $lang->trans('ogniter.community_tools') }}</a></li>
                    <li><a href="site/evolution"><i class="icon-align-right"></i> {{ $lang->trans('ogniter.historical_statistics') }}</a></li>
                </ul>
            </div>
            @if ( !$agent->isMobile() )
                @include('classic.partials.ads.sidebar-ad')
            @endif
            <div class="box above-me clearfix visible-desktop">
                @include('classic.partials.social-buttons')
            </div>
        </div>

        <div id="content" class="span10">
            @include('classic.partials.ads.top-ad')
            <div class="row-fluid">
                <div class="span8">
                    @yield('breadcrumb')
                </div>
                <div class="span4">
                    @include('classic.partials.ads.mini-ad')
                </div>
            </div>
            <div class="row-fluid">
                @yield('content')
            </div>
            <div style="margin-top: 10px">
                @include('classic.partials.ads.bottom-ad')
            </div>
        </div>
    </div>
    <hr>
    <footer>
        <p class="pull-left">
            <a href="http://buscandoquehacer.com" target="_blank"> &copy; Donato Laynes</a> 2012-2016 | <a href="humans">Collaborators</a></p>
        <p class="pull-right">
            <a href="http://www.gameforge.com" target="_blank">{{ $lang->trans('ogniter.og_game_created_by') }}</a>.</p>
    </footer>
</div>
<script src="{{ $cdnHost }}js/combined2016.js"></script>
<script src="{{ $cdnHost }}js/mvc/routes/main.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('scripts')
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_EN/all.js#xfbml=1&appId=453851131318585";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
</body>
</html>