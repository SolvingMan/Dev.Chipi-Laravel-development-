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
                                    {{ $category->catName }}
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
                                                                   {{ $category['sub'][$j]->subcatName }}
                                                                </strong>
                                                           </li>

                                                           <?php $k = 0;?>
                                                               @foreach($category['sub'][$j]->sub as $subSub)
                                                                   <li>
                                                                       <a href="/{{$siteslug}}/category/{{$subSub->subSubcatName}}/{{
                                                                       $subSub->subSubcatId}}/1">
                                                                           {{ $subSub->subSubcatName }}
                                                                       </a>
                                                                   </li>
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