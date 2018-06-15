@foreach($categories as $category)
    @if(count($category['sub'])==0)
        @continue
    @endif
    <li class="parent">
        <a href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                        <span class="icon">
                            <i class="fa {{$category['catIcon']}}"></i>
                        </span>
            @if(Lang::getLocale()=='he')
                {{ $category->catName }}
            @elseif(Lang::getLocale()=='en')
                {{ $category->catEnglish }}
            @endif
        </a>
        <span class="toggle-submenu"></span>
        <div class="submenu"
             style='background: white url({!! asset("images/icon/index1/".$category['catPic']) !!}) no-repeat left center;'>
            <ul class="categori-list clearfix">

                @for($i=0;$i<count($category['sub']);$i+=3)
                    <div class="row">
                        <?php $rows = 0; ?>
                        @for($j=$i;$j<$i+3;$j++)
                            {{--break if there is no more subcategories to show--}}
                            @if($j==count($category['sub']))
                                @break(1)
                            @endif

                            {{--continue if there is already 3 columns--}}
                            <div class="col-sm-4 col-md-3">
                                <strong class="title">
                                    <a href=""
                                       style="pointer-events: none">
                                        @if(Lang::getLocale()=='he')
                                            {{ $category['sub'][$j]->subcatName }}
                                        @elseif(Lang::getLocale()=='en')
                                            {{ $category['sub'][$j]->subcatEnglish }}
                                        @endif
                                    </a>
                                </strong>
                                <ul>
                                    <?php $k = 0;?>
                                    @foreach($category['sub'][$j]->sub as $subSub)
                                        @if(++$k>5)
                                            <li>
                                                <a class="submenu-all-category" href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                                                    @lang('general.more_categories')
                                                </a>
                                            </li>
                                            @break(1)
                                        @endif
                                        @if($subSub->type == 0)
                                                <li>
                                                    <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->aliexpressId}}/1">
                                                        @if(Lang::getLocale()=='he')
                                                            {{ $subSub->subSubcatName }}
                                                        @elseif(Lang::getLocale()=='en')
                                                            {{ $subSub->englishName }}
                                                        @endif
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="/{{$siteslug}}/search/{{$subSub->search}}/1">
                                                        @if(Lang::getLocale()=='he')
                                                            {{ $subSub->subSubcatName }}
                                                        @elseif(Lang::getLocale()=='en')
                                                            {{ $subSub->englishName }}
                                                        @endif
                                                    </a>
                                                </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endfor
                    </div>
                @endfor
            </ul>
        </div>
    </li>
@endforeach
<li class="parent">
    <a href="/aliexpress/categoryList" class="all-category">
    <span class="icon">
         <i class="fa fa-plus" aria-hidden="true"></i>
    </span>
        @lang('general.all_categories')
    </a>
</li>