@foreach($categories as $category)
    @if(count($category['sub'])==0)
        @continue
    @endif
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
            {{ $category->catName }}
        </button>
        <ul class="dropdown-menu">
            @foreach($category['sub'] as $sub)
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                            data-toggle="dropdown">
                        {{ $sub->subcatName }}
                    </button>
                    <ul type="circle">
                        @foreach($sub->sub as $subSub)
                            <li>
                                <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->aliexpressId}}">
                                    {{ $subSub->subSubcatName }} :
                                    {{ $subSub->aliexpressId }} :
                                    {{ $subSub->englishName }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </ul>
    </div>
@endforeach