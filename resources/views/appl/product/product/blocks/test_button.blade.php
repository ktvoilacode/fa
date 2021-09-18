@if($test->status)
    @if(\auth::user())
    @if(!\auth::user()->attempt($test->id))
    <a href="{{ route('test.instructions',$test->slug)}}?product={{$obj->slug}}" class="btn btn-primary mb-1 "><i class="fa fa-paper-plane"></i> Take Test</a>
    @else
    @if($test->test_id == 3 || $test->test_id == 4)
    <a href="{{ route('test.try',$test->slug)}}?product={{$obj->slug}}" class="btn btn-secondary mb-1 "><i class="fa fa-eye"></i> View Response</a>
    @else
    <a href="{{ route('test.analysis',$test->slug)}}?product={{$obj->slug}}" class="btn btn-secondary mb-1 "><i class="fa fa-bar-chart"></i> Test Report</a>
    @endif
    @endif
    @else
    <a href="{{ route('test.instructions',$test->slug)}}?product={{$obj->slug}}" class="btn btn-primary mb-1 "><i class="fa fa-paper-plane"></i> Take Test</a>
    @endif
    @endif