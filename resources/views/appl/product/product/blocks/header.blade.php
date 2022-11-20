<div class=" container ">
  <div class="pb-2">
    @auth
    &nbsp;<i class="fa fa-angle-left"></i>&nbsp;
    <a href="{{ url('/home')}}" class="text-primary">  back to dashboard </a> 
    @else
     &nbsp;<i class="fa fa-angle-left"></i>&nbsp;
    <a href="{{ url('/products')}}" class="text-primary">  All Products </a> 
    @endauth
  </div>
  <h1 class="h3 mb-0"><b> {{ strip_tags($obj->name) }} </b>
    @can('update',$obj)
    <a href="{{ route($app->module.'.edit',$obj->id) }}" class="h5" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
    @endcan

    @if(count($obj->tests))
      <span class="float-md-right text-secondary" > {{ count($obj->tests)}}  @if(count($obj->tests)>1)Tests @else Test @endif </span> 
    @endif
  </h1>
</div>