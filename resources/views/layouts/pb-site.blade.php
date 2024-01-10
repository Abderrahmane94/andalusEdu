@php
    $setting = generalSetting();
    App::setLocale(getUserLanguage());
    $ttl_rtl = userRtlLtl();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (isset($ttl_rtl) && $ttl_rtl == 1) dir="rtl" class="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset($setting->favicon) }}" type="image/png" />
        <title>{{ $setting->site_title ? $setting->site_title : 'Infix Edu ERP' }} | {{(@$page) ? @$page->title : __('common.home')}}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="_token" content="{!! csrf_token() !!}" />

        @if (!empty($page->description) )
            <meta name="description" content="{{ $page->description }}" />
        @endif

        @if( config('pagebuilder.add_bootstrap') === 'yes' )
            @if (isset($ttl_rtl) && $ttl_rtl == 1)
                <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/bootstrap.rtl.min.css') }}">
            @else
                <link rel="stylesheet" href="{{ asset('public/vendor/optionbuilder/css/bootstrap.min.css') }}">
            @endif
        @endif
        <!-- Main css -->
        <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/fontawesome.all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/dataTables.jqueryui.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/theme/'.activeTheme().'/css/responsive.jqueryui.min.css') }}">
        <link rel="stylesheet" href="{{asset('public/theme/'.activeTheme().'/css/style.css')}}">
        <link rel="stylesheet" href="{{ asset('public/whatsapp-support/style.css') }}">

        @stack(config('pagebuilder.style_var'))
    </head>
    <body>
        <div class="bg-shade"></div>

        <main class="mainbag">
            @yield(config('pagebuilder.site_section'))
        </main>

        <!-- background overlay -->
            <div class="bg-shade"></div>
        <!-- background overlay -->

        @if( config('pagebuilder.add_jquery') === 'yes' )
            <script src="{{ asset('public/vendor/optionbuilder/js/jquery.min.js') }}"></script>
        @endif

        @if( config('pagebuilder.add_bootstrap') === 'yes' )
            <script defer src="{{ asset('public/vendor/optionbuilder/js/bootstrap.min.js') }}"></script>
        @endif
        <script>
            window._locale = '{{ app()->getLocale() }}';
            window._rtl = {{ userRtlLtl() == 1 ? 'true' : 'false' }};
        </script>
        <script src="{{asset('public/theme/'.activeTheme().'/js/jquery.dataTables.min.js')}}"></script>
        <script>
            $('body').append('<!--back to top btn--><a href="#" class="backtop"><i class="far fa-long-arrow-alt-up"</i>');
            $(window).on('scroll', function() {
                var x = $(window).scrollTop();
                if (x > 700) {
                    $('.backtop').addClass('show')
                } else {
                    $('.backtop').removeClass('show')
                }
            });
            
            $(".common_data_table table").DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: "Search ...",
                    search: "<i class='far fa-search datatable-search'></i>",
                },
            });
        </script>
        <script src="{{ asset('public/whatsapp-support/scripts.js') }}"></script>
        @stack(config('pagebuilder.script_var'))  
    </body>
</html>
