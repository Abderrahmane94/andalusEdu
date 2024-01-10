@if ($courses->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
    <p class="text-center text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
            href="{{ URL::to('/course-list') }}">@lang('edulia.add_course')</a></p>
@else
    @foreach ($courses as $key => $course)
        @php
            $color = '';
            if ($key % 4 == 1) {
                $color = 'sunset-orange';
            } elseif ($key % 4 == 2) {
                $color = 'green';
            } elseif ($key % 4 == 3) {
                $color = 'blue';
            } else {
                $color = 'orange';
            }
        @endphp
        <div class="col-lg-{{ $column }}">
            <a href='{{ route('frontend.course-details', $course->id) }}' class="course_item">
                <div class="course_item_img">
                    <div class="course_item_img_inner">
                        <img src="{{ asset($course->image) }}" alt="{{ $course->courseCategory->category_name }}">
                    </div>
                    @if($course->courseCategory->category_name)
                        <span
                            class="course_item_img_status {{ $color }}">{{ $course->courseCategory->category_name }}
                        </span>
                    @endif
                </div>
                <div class="course_item_inner">
                    <h4>{{ $course->title }}</h4>
                </div>
            </a>
        </div>
    @endforeach
@endif
