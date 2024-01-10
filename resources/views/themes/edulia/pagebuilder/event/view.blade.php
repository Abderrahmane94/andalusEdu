@pushonce(config('pagebuilder.site_style_var'))
    <style>
        a.event_title h4 {
            max-width: 15ch;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpushonce
@php
    $emptyImage = empty(pagesetting('event_img'));
@endphp
<section class="section_padding events {{!$emptyImage ? 'index-events' : ''}}" style="background-image:url('{{(pagesetting('event_bg_img') ? pagesetting('event_bg_img')[0]['thumbnail'] : '')}}')">
    <div class="container">
        <div class="row {{!$emptyImage ? 'align-items-center' : ''}}">
            @if (!$emptyImage)
                <div class="col-md-5">
                    <div class="events_preview_img">
                        <img src="{{ pagesetting('event_img')[0]['thumbnail'] }}" alt="{{ __('edulia.Image') }}">
                    </div>
                </div>
            @endif
            <div class="col-md-{{!$emptyImage ? 7 : '12 text-center' }}">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('event_sub_heading') }}</span>
                    <h2>{{ pagesetting('event_heading') }}</h2>
                    <p>{!! pagesetting('event_description') !!}</p>
                </div>
                @if (!$emptyImage)
                    <x-event :count="pagesetting('event_count')"> </x-event>
                @endif
            </div>
        </div>
        @if ($emptyImage)
            <div class="row">
                <div class="col-lg-7 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                    <x-event :count="pagesetting('event_count')"> </x-event>
                </div>
            </div>
        @endif
    </div>
</section>
