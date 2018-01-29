@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
@section('content')
    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <center><h1>Achievements</h1></center>
                <legend></legend>
                <div class="panel panel-body">

                    @if (isset($error))
                        @foreach($error as $errors)
                            <?php echo "<div id=\"message\" class=\"alert alert-danger col-md-12\">" . $errors . "</div>"  ?>
                        @endforeach
                    @endif

                    @if (isset($succes))
                        @foreach($succes as $success)
                            <?php echo "<div id=\"message\" class=\"alert alert-success col-md-12\">" . $success . "</div>"; ?>
                        @endforeach
                    @endif

                    <form action="" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <center>
                            <div class="input-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        Kies een foto voor deze Achievement.... <input type="file" name="image"
                                                                                     style="display: none;" multiple>
                                    </span>
                                </label>
                            </div>
                        </center>
                        <div class="form-group">
                            <label>Achievement Naam</label>
                            <input required type="text" class="form-control" name="name" aria-describedby="name"
                                   placeholder="Naam">
                        </div>

                        <div class="form-group">
                            <label>Conditie voor deze achievement</label>
                            <select name="condition" id="condition" class="form-control">
                                <option value="win">wins</option>
                                <option value="winstreak">winstreak</option>
                                <option value="lose">losses</option>
                                <option value="losestreak">lose streak</option>
                                <option value="score">score</option>
                            </select>

                        </div>

                        <div class="form-group">
                            <label>Eis voor deze achievement</label>
                            <input type="number" class="form-control" name="requirement" aria-describedby="name"
                                   placeholder="100">
                        </div>

                        <div class="form-group">
                            <label>Achievement Beschrijving</label>
                            <textarea required class="form-control" name="description" placeholder="Beschrijving"
                                      rows="5"
                                      id="comment"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Opslaan</button>
                        <a href="{{route('adminAchievement')}}" style="color:green">
                            <span class="btn btn-primary">Terug</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    $(document).ready(function () {
        setTimeout(function () {
            $('#message').addClass('hide');
        }, 4000);
    });
</script>