<div class="container-fluid">
    <div class="row darkred" style="min-width: 100%;">
        <h1 class="darkred" style="font-size: 175%;"><a href="{{ route('root') }}" style="color: white">Boardgame Tournament 1</a></h1>
        <div style="text-align: center; background-color: darkred;">
            <div class="row">
                <form method="get" class="navbar-form form-inline" action="{{ route('search') }}">
                    <div class="input-group-btn row big">
                        <span class="col-md-2">
                        </span>
                        <!-- search bar -->
                        <input type="text" class="form-control col-md-6 col-xs-6"
                               style="min-width: 55%; max-width: 100%; border-radius: 5px 0px 0px 5px; height: 2.5em;"
                               name="search_string"
                               placeholder="Bordspel"
                               value="{{ (isset($_GET['search_string']) ? $_GET['search_string'] : '') }}">
                        <select name="search_type" class="form-control col-md-2 col-xs-3" style="border-radius: 0px; height: 2.5em;">
                            <option value="name" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'name' ? 'selected' : '') }}>
                                Naam
                            </option>
                            <option value="minp" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'minp' ? 'selected' : '') }}>
                                Min spelers
                            </option>
                            <option value="maxp" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'maxp' ? 'selected' : '') }}>
                                Max spelers
                            </option>
                            <option value="time" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'time' ? 'selected' : '') }}>
                                Speeltijd
                            </option>
                        </select>
                        <button class="btn btn-default col-md-1 col-xs-3" id="searchButton"
                                style="border-radius: 0px 5px 5px 0px; height: 2.5em;">Zoek
                        </button>
                        <span class="col-md-1"></span>
                    </div>
                </form>
            </div>

            @include('layouts._nav')

        </div>
    </div>
</div>