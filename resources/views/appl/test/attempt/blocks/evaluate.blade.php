<div>
	<h6>{{ucfirst(str_replace('-',' ',$param))}}</h6>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="{{$item->qno}}_{{$param}}" id="inlineRadio1" value="0" @if(isseet($marking[$item->qno][$param]))@if($marking[$item->qno][$param]=="0") checked @endif @endif>
  <label class="form-check-label" for="inlineRadio1">NA</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="{{$item->qno}}_{{$param}}" id="inlineRadio1" value="1" @if(isseet($marking[$item->qno][$param]))@if($marking[$item->qno][$param]=="1") checked @endif @endif>
  <label class="form-check-label" for="inlineRadio1">1</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="{{$item->qno}}_{{$param}}" id="inlineRadio2" value="2" @if(isseet($marking[$item->qno][$param]))@if($marking[$item->qno][$param]=="2") checked @endif @endif>
  <label class="form-check-label" for="inlineRadio2">2</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="{{$item->qno}}_{{$param}}" id="inlineRadio3" value="3" @if(isseet($marking[$item->qno][$param]))@if($marking[$item->qno][$param]=="3") checked @endif @endif>
  <label class="form-check-label" for="inlineRadio3">3 </label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="{{$item->qno}}_{{$param}}" id="inlineRadio4" value="4" @if(isseet($marking[$item->qno][$param])) @if($marking[$item->qno][$param]=="4") checked @endif @endif>
  <label class="form-check-label" for="inlineRadio3">4 </label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="{{$item->qno}}_{{$param}}" id="inlineRadio5" value="5" @if(isseet($marking[$item->qno][$param])) @if($marking[$item->qno][$param]=="5") checked @endif @endif>
  <label class="form-check-label" for="inlineRadio3">5 </label>
</div>
</div><br>