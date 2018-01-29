@extends('layouts.app')
@section('content')
    <div>
        <div class="row">
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
            <div class="col-md-12">
                <center><h1>Achievement</h1></center>
                <legend></legend>
                <form method="post" action="{{ route('searchAchievementsAdmin') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-md-offset-8">
                            <div class="input-group">
                                <select required class="js-example-basic-multiple" style="width: 100%"
                                        name="achievements[]" multiple="multiple" id="achievement">
                                    @if(is_string(old('achievement')) && old('achievement') !== '')
                                        <option value="{{ old('achievement') }}"
                                                selected>{{ \App\achievement::where('id', 1)->pluck('name')[0] }}</option>
                                    @endif
                                </select>
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <br>
                <div class="table-responsive">
                    <table style="width:100%" class="table table-striped" id="">
                        <thead>
                        <tr>
                            <th>Afbeelding</th>
                            <th>Achievement naam</th>
                            <th>Conditie</th>
                            <th>Conditie eis</th>
                            <th>Opties</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($achievements as $achievement)
                            <tr>
                                <td>
                                    @if (isset($achievement->image))
                                        <img alt="Embedded Image" src="data:image/png;base64,{{$achievement->image}}"
                                             width="50" height="50"/>
                                    @endif
                                </td>
                                <td>{{ $achievement->name }}</td>
                                <td>{{ $achievement->condition }}</td>
                                @if(isset($achievement->requirement))
                                    <td>{{ $achievement->requirement }}</td>
                                @else
                                    <td>There is no requirement set</td>
                                @endif
                                <td>
                                    <div class="button-container">
                                        <form id="checkform{{$achievement->id}}"
                                              action="{{  route('adminAchievementCheck', ['id' => $achievement->id]) }}"
                                              method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="put">
                                            <i class="fa fa-pencil text-primary"
                                               onclick="document.getElementById('checkform{{$achievement->id}}').submit();"
                                               aria-hidden="true"></i>
                                        </form>

                                        <form id="deleteform{{$achievement->id}}"
                                              action="{{  route('adminAchievementDelete', ['id' => $achievement->id]) }}"
                                              method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="delete">
                                            <i class="fa fa-times text-danger"
                                               onclick='deleteitem({{$achievement->id}},"{{$achievement->name}}" );'
                                               aria-hidden="true"></i>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{route('adminAchievementCreate')}}" style="color:green">
                    <span class="btn btn-primary">Voeg een nieuwe achievement toe</span>
                </a>
                <center>{{ $achievements->links() }}</center>


                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        $(document).ready(function () {
            setTimeout(function () {
                $('#message').addClass('hide');
            }, 4000);
        });

        function deleteitem(id, name) {
            if (confirm("Weet u zeker dat u " + name + " wilt verwijderen?")) {
                document.getElementById('deleteform' + id).submit();
            }
            else {
                e.preventDefault();
            }
        }

        $(document).ready(function () {
            $('#achievement').select2({
                placeholder: "Kies een achievement",
                ajax: {
                    url: '{{ route('achievementsJson') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return data;
                    },
                    cache: true,
                    delay: 250
                }
            });
        });

    </script>
@endsection