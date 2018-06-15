@extends('layouts.main')
@section('content')
    <!-- MAIN -->
    <main class="site-main">
        <div class="columns container">
            <div class="row">
                <div class="products  products-grid">
                    <ol class="product-items row">
                        @for($i=0;$i<count($products);$i+=1)
                            <li class="col-sm-3 product-item ">
                                <div class="product-item-opt-1">
                                    <div class="product-item-info">
                                        <a href="">
                                            <div class="product-item-photo">
                                                <div class="loader-wrap"></div>
                                                <div class="product-item-img">
                                                    <img class="category-fixed-size-image"
                                                         src='{{ $products[$i]->productImage }}'>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="product-item-detail">
                                            <strong class="product-item-name"
                                                    style="min-height: 60px; height: 60px; overflow: hidden;">
                                                <a href="">
                                                    <span>{{$products[$i]->productTitle}}</span>
                                                </a>
                                            </strong>
                                            <div class="clearfix">
                                                <div class="product-item-price">
                                                    <span class="price">start:{{$products[$i]->startTime}}</span>
                                                    <span class="price">end:{{$products[$i]->endTime}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endfor
                    </ol><!-- list product -->
                </div> <!-- List Products -->
            </div>
            <div class="result bleach row">
                <ul></ul>
            </div>
        </div>
    </main>
@stop