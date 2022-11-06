<div class=" container ">
  <div class="row pt-4 pb-4">
    <div class="col-12 col-md-8">
      <h1 class="h3 mb-0 mt-2"><b><i class="fa fa-check-square-o"></i> Test Packs</b></h1>
    </div>

    <div class="col-12 col-md-4">
      <form class="form-inline float-md-right mt-3 mt-md-0" method="GET" action="{{ route($app->module.'.public') }}">
            <div class="input-group input-group-lg">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fa fa-search"></i></div>
              </div>
              <input class="form-control " id="search" name="item" autocomplete="off" type="search" placeholder="Search" aria-label="Search" 
              value="{{Request::get('item')?Request::get('item'):'' }}">
            </div>
          </form>
    </div>
  </div>
  
</div>