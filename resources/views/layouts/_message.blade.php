@if (Session::has('success'))
    <div class="alert alert-success alert-dismissable alert-top" style="text-align: center;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session('success') }}
    </div>
@endif

@if(Session::has('error'))
  <div class="alert alert-danger alert-dismissable alert-top" style="text-align: center;">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{ session('error') }}
  </div>
@endif
