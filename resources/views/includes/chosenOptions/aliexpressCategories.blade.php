@foreach($categories as $category)
    @if(count($category['sub'])==0)
        @continue
    @endif
    <option value="-1" selected>
        @lang('general.all_categories')
        @endlang
    </option>
    @foreach($category['sub'] as $sub)
        @foreach($sub->sub as $subSub)
            <option value="{{ $subSub->aliexpressId }}">{{ $subSub->subSubcatName }}</option>
        @endforeach
    @endforeach
@endforeach
