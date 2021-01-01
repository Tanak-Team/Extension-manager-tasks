
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Comment</h3>
  </div>
  <div class="box-body box-footer box-comments">
    @forelse ($actions as $action)
        <div class="box-comment">
            <!-- User image -->
            <img class="img-circle img-sm" src="{{$action->user->avatar }}" alt="User Image">
    
            <div class="comment-text">
                <span class="username">
                    <span>
                    {{$action->user->name}}
                    
                    </span>

                    <span class="text-muted pull-right">{{$action->created_at}}</span>
                </span>
                {{$action->comment}}
                <div>
                  Status: <span class="label" style="background-color:{{$action->process->color}}">{{$action->process->name}}</span>
                  <span class="text-muted pull-right">
                    <b>Assignee:</b>
                    <span>{{$action->assinger->name}}</span>
                  @if($action->is_read)<span class="label" style="background-color:#555">Readed</span>@endif
                </div>
            </div>
      </div>
    @empty
      <p>{{__('Not found data')}}</p>
    @endempty
  </div>
</div>