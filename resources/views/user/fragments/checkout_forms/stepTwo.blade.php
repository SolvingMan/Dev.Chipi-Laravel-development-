<div class="page-content checkout-page" style="margin-top: 0">
    <div class="box-border">
        <div class="product-list">
            @include('user.fragments.productsTable')
        </div>
        <div class="row" style="width: 70%;">
            <div class="col-xs-12">
                <div class="form-group">
                    <label style="float:right" for="notice">@lang('general.notice')</label>
                    <input type="text" class="form-control" id="notice">
                </div>
            </div>
        </div>
    </div>
</div>