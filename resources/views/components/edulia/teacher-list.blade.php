<div class="row">
    @if ($teachers->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
        <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                href="{{ URL::to('/expert-teacher') }}">@lang('edulia.teacher_list')</a></p>
    @else
        @foreach ($teachers as $teacher)
            <div class="col-lg-{{ $column }}">
                <a href='#' class="teacher_wrapper">
                    <div class="teacher_wrapper_img"><img src="{{ asset(@$teacher->image) }}" alt=""></div>
                    <h4>{{ $teacher->name }}</h4>
                    <p>{{ $teacher->designation }}</p>
                </a>
            </div>
        @endforeach
    @endif
</div>
