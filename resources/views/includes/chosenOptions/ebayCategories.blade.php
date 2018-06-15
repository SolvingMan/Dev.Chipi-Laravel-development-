@foreach($categories as $category)
    <option value="{{ $category->children[0]->categoryID }}"
            selected>{{ $category->children[0]->categoryName }}</option>
    @foreach($category->children as $key=>$sub)
        @if($key==0)
            @continue
        @endif
        <option value="{{ $sub->categoryID }}">{{ $sub->categoryName }}</option>
    @endforeach
@endforeach