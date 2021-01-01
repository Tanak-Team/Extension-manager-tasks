
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">{{$project->name}}</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-8">
              {!! $table !!}
          </div>
          <div class="col-md-4">
            <p class="text-center">
              <strong>Goal Completion</strong>
            </p>
            @forelse ($project->process as $process)
                <div class="progress-group">
                    <span class="progress-text">{{$process->name}}</span>
                    <span class="progress-number"><b>{{$process->tasks->count()}}</b>/{{$project->tasks->count()}}</span>
                    <div class="progress sm">
                    @php
                        $percent = ($project->tasks->count()) ? ($process->tasks->count()/$project->tasks->count())*100 : 0;
                    @endphp
                        <div class="progress-bar" style="background-color: {{$process->color}}; width: {{$percent}}%"></div>
                    </div>
              </div>
            @empty

            @endforelse
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.box-footer -->
      <div class="box-footer clearfix" style="">
        <a href="{{route('task-manager.tasks.index',$project->id)}}" class="btn btn-sm btn-default btn-flat pull-right">View All</a>
      </div>
    </div>
    <!-- /.box -->
