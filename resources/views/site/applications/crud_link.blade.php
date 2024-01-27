<div class='row'>
    @foreach($data as $key => $elem)
        @if($key === 'destroy')
            <a style='background-color: {{setting("color.{$key}")}};
               color: {{setting('color.' . $key) ? 'white' : 'black'}}'
               href='{{$elem}}' class='m-1 col edit btn btn-sm'
               onclick="return confirm('{{$confirm}}')">{{__($key)}}</a>
        @else
            <a style='background-color: {{setting("color.{$key}")}};
               color: {{setting('color.' . $key) ? 'white' : 'black'}}'
               href='{{$elem}}' class='m-1 col edit btn btn-sm'>{{__($key)}}</a>
        @endif
    @endforeach
</div>
