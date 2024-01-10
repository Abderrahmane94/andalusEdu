<div class="row">
    <div class="col-md-12 text-{{ pagesetting('button_alignment') }}">
        @if (!empty(pagesetting('button_items')))
            @foreach (pagesetting('button_items') as $item)
                <a id="{{ !empty($item['button_id']) ? $item['button_id'] : '' }}"
                    href="{{ !empty($item['button_link']) ? $item['button_link'] : '' }}"
                    target="{{ !empty($item['button_link_option']) ? $item['button_link_option'] : '' }}"
                    class="site_btn {{ !empty($item['button_type']) ? $item['button_type'] : '' }}"
                    style="padding: {{ !empty($item['button_size']) ? $item['button_size'] : '' }}; font-size: {{ !empty($item['button_text_size']) ? $item['button_text_size'] : '' }};">
                    {{ !empty($item['button_text']) ? $item['button_text'] : '' }}
                </a>
            @endforeach
        @endif
    </div>
</div>
