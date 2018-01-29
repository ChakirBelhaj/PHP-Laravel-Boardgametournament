@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
@section('content')
    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <center><h1>Achievement</h1></center>
                <legend></legend>
                <div class="panel panel-body">

                    @if (isset($error))
                        @foreach($error as $errors)
                            <?php echo  "<div id=\"message\" class=\"alert alert-danger col-md-12\">". $errors ."</div>"  ?>
                        @endforeach
                    @endif

                    @if (isset($succes))
                        @foreach($succes as $success)
                            <?php echo "<div id=\"message\" class=\"alert alert-success col-md-12\">". $success ."</div>"; ?>
                        @endforeach
                    @endif
                    <form action="{{route('adminAchievementUpdate', ['id' => $achievements->id])}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <center>
                            <div  id="image" class="input-group hidden">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        Kies een foto voor deze prestatie.. <input hidden type="file" name="image" style="display: none;" multiple>
                                    </span>
                                </label>
                            </div>
                        </center>


                        <center>
                            @if (isset($achievements->image))
                                <br>
                                <img alt="Embedded Image" src="data:image/png;base64,{{$achievements->image}}" width="100" height="100" />
                            @endif
                        </center>

                        <div class="form-group">
                            <label>Achievement Name</label>
                            <input required id="name" type="text" class="form-control" name="name" value="{{$achievements->name}}" aria-describedby="name" placeholder="Enter achievement name" readonly>
                        </div>

                        <div  class="form-group">
                            <label>Condition to get achievement</label>
                            <select style="pointer-events: none;" readonly name="condition" id="condition" class="form-control">
                                <option <?php if ($achievements->condition == 'win')        { echo 'selected';} ?> value="win">wins</option>
                                <option <?php if ($achievements->condition == 'winstreak')  { echo 'selected';} ?> value="winstreak">winstreak</option>
                                <option <?php if ($achievements->condition == 'lose')       { echo 'selected';} ?> value="lose">losses</option>
                                <option <?php if ($achievements->condition == 'losestreak') { echo 'selected';} ?> value="losestreak">lose streak</option>
                                <option <?php if ($achievements->condition == 'score')      { echo 'selected';} ?> value="score">score</option>
                            </select>

                        </div>

                        <div class="form-group">
                            <label>Requirement to get achievement</label>
                            <input readonly id="requirement"  type="number" class="form-control" name="requirement" value="{{$achievements->requirement}}" aria-describedby="name" placeholder="requirement">
                        </div>

                        <div class="form-group">
                            <label>Achievement Description</label>
                            <textarea id="description" required class="form-control" name="description" placeholder="Description" rows="5" readonly>{{$achievements->description}}</textarea>
                        </div>

                        <div class="form-group">
                            <button id="savechanges"type="submit" class="btn btn-primary hidden">Opslaan</button>

                            <button id="edit" type="button" class="btn btn-primary" onclick="editAttributes();" >Wijzigen</button>

                            <a href="{{route('adminAchievement')}}" style="color:green">
                                <span class="btn btn-primary">Terug</span>
                            </a>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection

<script>

    function editAttributes() {
        document.getElementById('name').removeAttribute('readonly');
        document.getElementById('description').removeAttribute('readonly');
        document.getElementById('image').removeAttribute('hidden');
        document.getElementById('condition').removeAttribute('readonly');
        document.getElementById('condition').removeAttribute('hidden');
        document.getElementById('condition').removeAttribute('style');
        document.getElementById('requirement').removeAttribute('readonly');

        $('#image').removeClass('hidden');
        $('#savechanges').removeClass('hidden');
        $('#edit').addClass('hidden');
    }

    $(document).ready(function(){
        setTimeout(function(){
            $('#message').addClass('hide');
        }, 4000);
    });
</script>