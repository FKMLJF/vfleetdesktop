<div class="md-form">

    <select id="hiba_id"  name="hiba_id" disabled onchange="onchangeselect('hiba_id')" class="form-control select">
        <option value="-1"></option>
        @foreach($select2 as $item)
            <option value="{{$item->azonosito}}"
            >Hibajegy azonosító: {{$item->azonosito}}</option>
        @endforeach
    </select>
    <label for="auto_azonosito" class="{!! !empty($model['auto_azonosito'])?'active':'' !!}">Hibajegy</label>
    @if ($errors->has('hiba_id'))
        <div class=" alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Hiba!</strong> {{ $errors->first('hiba_id') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
