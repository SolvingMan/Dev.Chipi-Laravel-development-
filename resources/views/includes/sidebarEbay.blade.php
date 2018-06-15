@foreach($categories as $item)
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
            {{ $item->categoryName }}
        </button>
        <ul class="dropdown-menu">
            <div class="dropdown">
                <ul type="circle">
                    @foreach($item->children as $sub)
                        <li>
                            <form method="get" action="/{{$siteslug}}/category/{{$sub->categoryName}}/1">
                                <input type="hidden" name="id" value="{{$sub->categoryID}}">
                                <button type="submit">
                                    {{ $sub->categoryName }} : {{ $sub->categoryID }}
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </ul>
    </div>
@endforeach