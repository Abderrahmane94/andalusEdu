@pushonce(config('pagebuilder.site_style_var'))
    <style>
        iframe {
            width: 100% !important;
        }
    </style>
@endpushonce
<div class="col-lg-12">
    <div class="contacts_info">
        <p>{!! pagesetting('google_map_editor') !!}</p>
        <div class="google_map">
            {!! pagesetting('google_map_key') !!}
        </div>
    </div>
</div>
