@extends('panel::layouts.master')

@section('content')

    @if($category_count == 1)
        <div class="alert alert-info" role="alert">
            <h2 class="h4">Getting started?</h2>
            <ul>
                <li>Start off by adding some <a href="/panel/categories" class="alert-link">categories</a></li>
                <li>Customize the <a href="/panel/settings" class="alert-link">settings</a> of your website</a></li>
                <li>Perhaps even add a few <a href="<?= route('create.index') ?>" class="alert-link">listings</a></li>
                <li>Add your <a href="/panel/settings" class="alert-link">stripe keys</a> to start earning</li>
                <li>Tell the world!</li>
            </ul>
        </div>
    @endif
    <h1 class="h3">Welcome {{auth()->user()->name}},</h1>
    <h2 class="h5">What would you like to do today?</h2>
    <br />
    <br />

    <div class="row">

        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="/images/admin/tag.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#" class="text-muted font-weight-bold">Listings & Categories</a></li>
                        <li class="pb-1"><a href="/panel/listings">View Listings</a></li>
                        <li class="pb-1"><a href="<?= route('create.index') ?>">Post new listing</a></li>
                        <li class="pb-1"><a href="/panel/categories">View category listing</a></li>
                        <li class="pb-1"><a href="/panel/categories/create">Create new category</a></li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="/images/admin/orders.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Users &amp; Orders</a></li>
                        <li class="pb-1"><a href="/panel/users">View Buyers &amp; Sellers</a></li>
                        <li class="pb-1"><a href="/panel/orders">View Orders</a></li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="/images/admin/categories.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Content</a></li>
                        <li class="pb-1"><a href="/panel/pages">Manage pages</a></li>
                        <li class="pb-1"><a href="/panel/menu">Manage menu</a></li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="/images/admin/design.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Design</a></li>
                        @if(module_enabled('homepage'))
                        <li class="pb-1"><a href="/panel/addons/homepage">Customize homepage</a></li>
                        @endif
                        <li class="pb-1"><a href="/panel/themes">Themes &amp; CSS</a></li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="/images/admin/config.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Settings</a></li>
                        <li class="pb-1"><a href="/panel/settings">General settings</a></li>
                        <li class="pb-1"><a href="/panel/fields">Fields &amp; filters</a></li>
                        <li class="pb-1"><a href="/panel/pricing-models">Pricing models</a></li>
                    </ul>

                </div>

            </div>

        </div>

    </div>


@stop
