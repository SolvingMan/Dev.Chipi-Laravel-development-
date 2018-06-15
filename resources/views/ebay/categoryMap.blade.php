@extends('layouts.main')
@section('content')
    <main class="site-main">
        <div class="container category-map-wrap">
                     @foreach($categoriesList as $category)
                          @if(count($category['sub'])==0)
                              @continue
                          @endif

                          <section class="category-section">
                               <h3 class="section-title">
                                    <span class="icon">
                                        <i class="fa {{$category['catIcon']}}"></i>
                                    </span>
                                   @if(\Lang::getLocale()=='he')
                                       {{ $category->catName }}
                                   @elseif(\Lang::getLocale()=="en")
                                       {{ $category->catEnglish }}
                                   @endif
                               </h3>

                               <div class="section-content">
                                    @for($i=0;$i<count($category['sub']);$i+=6)
                                        <div class="row">
                                        <?php $rows = 0; ?>
                                             @for($j=$i;$j<$i+6;$j++)
                                                  {{--break if there is no more subcategories to show--}}
                                                  @if($j==count($category['sub']))
                                                       @break(1)
                                                  @endif

                                                  {{--continue if there is already 3 columns--}}

                                                  <div class="col-lg-2 col-sm-4 col-xs-12">
                                                       <ul class="category-list">
                                                           <li class="category-title">
                                                                <strong class="title">
                                                                    @if(\Lang::getLocale()=='he')
                                                                        {{ $category['sub'][$j]->subcatName }}
                                                                    @elseif(\Lang::getLocale()=="en")
                                                                        {{ $category['sub'][$j]->subcatEnglish }}
                                                                    @endif
                                                                </strong>
                                                           </li>

                                                           <?php $k = 0;?>
                                                               @foreach($category['sub'][$j]->sub as $subSub)
                                                                  @if($subSub->type == 0)
                                                                   <li>
                                                                       <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{$subSub->ebayId}}/1">
                                                                           @if(\Lang::getLocale()=='he')
                                                                               {{ $subSub->subSubcatName }}
                                                                           @elseif(\Lang::getLocale()=="en")
                                                                               {{ $subSub->subSubcatEnglish }}
                                                                           @endif
                                                                       </a>
                                                                   </li>
                                                                  @else
                                                                   <li>
                                                                       <a href="/{{$siteslug}}/search/{{$subSub->search}}/1">
                                                                           @if(\Lang::getLocale()=='he')
                                                                               {{ $subSub->subSubcatName }}
                                                                           @elseif(\Lang::getLocale()=="en")
                                                                               {{ $subSub->subSubcatEnglish }}
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
                                </div>
                          </section>
                     @endforeach
                </div>
    </main>
@stop