<input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
<form method="GET" action="">

   
    <div class="student_list_filters">
        <div class="row align-items-end">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="mb-2">@lang('edulia.academic_year')</div>
                <select id="academic_year_selector" class="w-100" name="academic_year">
                    <option data-display="@lang('edulia.select_academic_year') *" value="">@lang('edulia.select_academic_year') *</option>
                    @foreach ($academicYears as $academicYear)
                        <option @if(request('academic_year') && $academicYear->id == request('academic_year') ) selected  @endif value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" id="class_selector_div">
                <div class="mb-2">@lang('edulia.class')</div>
                <select id="class_selector" class="w-100" name="class">
                    @if( isset($req_data['class']) && $req_data['class'])
                        <option  selected  value="{{ $req_data['class']->id }}">{{  $req_data['class']->class_name }}</option>
                    @endif 
                    <option data-display="@lang('edulia.select_class') *" value="">@lang('edulia.select_class')</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" id="section_selector_div">
                <div class="mb-2">@lang('edulia.section')</div>
                <select id="section_selector" class="w-100" name="section">
                    @if(isset($req_data['section']) && $req_data['section'])
                        <option  selected  value="{{ $req_data['section']->id }}">{{  $req_data['section']->section_name }}</option>
                    @endif 
                    <option data-display="@lang('edulia.select_section') *" value="">@lang('edulia.select_section')</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <button type="submit" class="boxed_btn search_btn"><i class="fa fa-search"></i>
                    @lang('edulia.search')</button>
            </div>
        </div>
    </div>
</form>

@if ($students->isEmpty() && auth()->check() && auth()->user()->role_id == 1)
        <p class="text-left text-danger">@lang('edulia.no_data_available_please_go_to') <a target="_blank"
                href="{{ URL::to('/student-admission') }}">@lang('edulia.student_list')</a></p>
@elseif(count($students) > 0)
<div id="student_list" class="student_grid">
    @foreach ($students as $student)
        <div class="student_item">
            <div class="d-flex single-student-info">
                <div>
                    <img src="{{ file_exists($student->student_photo) ? asset($student->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}"
                        class="student_photo" alt="student photo">
                </div>
                <div class="flex-grow-1">
                    <div class="info">
                        <p class="student_name">
                            {{ $student->full_name }}
                        </p>
                        <div class="additional_info">
                            <p class="student_roll">
                                <b>@lang('edulia.roll_no'):</b> {{ $student->roll_no }}
                            </p>
                            <p class="student_id">
                                <b>@lang('edulia.admission_no'):</b> {{ $student->admission_no }}
                            </p>
                            <p class="student_class">
                                <b>@lang('edulia.class'):</b>
                                {{ $student->studentRecord->class->class_name }}({{ $student->studentRecord->section->section_name }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
