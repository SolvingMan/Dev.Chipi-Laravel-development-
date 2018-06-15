@foreach($categories as $category)
    @if(count($category['sub'])==0)
        @continue
    @endif
    <li class="parent">
        <a href="/{{$siteslug}}/categoryMap/{{$category->catId}}">
                                            <span class="icon">
                                                <i class="fa {{$category['catIcon']}}"></i>
                                            </span>
            {{ $category->catName }}
        </a>
        <span class="toggle-submenu"></span>
        <div class="submenu"
             style='background: white url({!! asset("images/icon/ebay/".$category['catPic']) !!}) no-repeat left center;
             @if($category['catPicWidth']!="")
             {{"background-size: ".$category['catPicWidth']." ".$category['catPicHeight'].";"}}
             @endif'>
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

                            <div class="col-sm-3">
                                <strong class="title">
                                    <a href=""
                                       style="pointer-events: none">{{ $category['sub'][$j]->subcatName }} </a>
                                </strong>
                                <ul>
                                    @foreach($category['sub'][$j]->sub as $key=>$subSub)
                                        @if($key<=14)
                                            <li>
                                                <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->subSubcatId}}/1">
                                                    {{ $subSub->subSubcatName }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @if(count($category['sub'][$j]->sub)>15)
                                <div class="col-sm-3">
                                    <strong class="title second-col"></strong>
                                    <ul>
                                        @foreach($category['sub'][$j]->sub as $key=>$subSub)
                                            @if($key>14)
                                                <li>
                                                    <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->subSubcatId}}/1">
                                                        {{ $subSub->subSubcatName }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endfor
                    </div>
                @endfor
            </ul>
        </div>
    </li>
@endforeach
<li class="parent">
    <a href="/next/categoryList" class="all-category">
    <span class="icon">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </span>
        @lang('general.all_categories')
    </a>
</li>