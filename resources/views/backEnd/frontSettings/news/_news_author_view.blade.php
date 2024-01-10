<div class="d-flex align-items-center gap_20 {{$comment->status == 0 ? 'unapproveBgRow' : ''}}">
    <img class="comment_user_img" src="{{ asset(getProfileImage($comment->user->id)) }}" alt="{{$comment->user->full_name}}">
    <div>
        <h5>
            {{$comment->user->full_name}} - {{$comment->user->roles->name}}
        </h5>
        <div>
            {{$comment->user->email}}
        </div>
    </div>
</div>
