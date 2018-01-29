<div class="form-group{{ $errors->has($type['field']) ? ' has-error' : '' }}">
  <label for="name" class="col-md-4 control-label">{{ $type['name'] }}</label>

  <div class="col-md-6">
    <input id="name" type="{{ $type['type'] }}" class="form-control" name="{{ $type['field'] }}" value="{{ old($type['field']) }}"  {{ ($type['req']) ? 'required' : null }} >

    @if ($errors->has($type['field']))
      <span class="help-block">
          <strong>{{ $errors->first($type['field']) }}</strong>
      </span>
    @endif
  </div>
</div>