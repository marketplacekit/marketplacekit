@extends('panel::layouts.master')

@section('content')
    <div class="container">
        <a href="{{route('panel.pricing-models.index')}}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

        <div class="row mb-3">
            <div class="col-sm-8">
                @if(!$pricing_model->exists)
                    <h2 class="mt-xxs">Adding new pricing model</h2>
                @else
                    <h2 class="mt-xxs">Editing pricing model: {{ $pricing_model->name }}</h2>
                @endif
            </div>
            <div class="col-sm-4">

            </div>

        </div>

        <div class="row">

            <div class="col-sm-8">

                <div class="panel panel-default">
                    <div class="panel-body">

                        @if(!$pricing_model->exists)
                            {!! Form::model($pricing_model, ['route' => ['panel.pricing-models.store', $pricing_model], 'method' => 'post']) !!}
                        @else
                            {!! Form::model($pricing_model, ['route' => ['panel.pricing-models.update', $pricing_model], 'method' => 'put']) !!}
                        @endif

                        <div class="form-group">
                                <label>Name</label>
                                {{ Form::text('name', null, ['class' => 'form-control']) }}
                            </div>
							
							<div class="form-group">
                                <label">Seller label</label>
                                {{ Form::text('seller_label', null, ['class' => 'form-control']) }}
								<small class="form-text text-muted">The text sellers will see when choosing a pricing method - e.g. Sell something, Rent something, List your service, Request something, List a freebie, Post an announcement</small>
                            </div>
							
                            <div class="form-group">
                                <label>Widget Type</label>
                                {{ Form::select('widget', ['announcement' => 'Announcement', 'buy' => 'Buy', 'book_date' => 'Book a Date', 'book_time' => 'Book a Time', 'request' => 'Request',], null, ['placeholder' => 'Select...', 'class' => 'form-control', 'required' => 'required']) }}
                            </div>                            

                            <div class="form-group">
                                <label>Unit name</label>
                                {{ Form::text('unit_name', null, ['class' => 'form-control', 'placeholder' => 'e.g. item, unit, KG, Room, Space']) }}
                                <small class="form-text text-muted">The seller will be able to see the unit name when setting the price. e.g. a hotel owner will see price per <i>room</i>, a merchant will see price per <i>item</i>. (singular only)</small>
                            </div>

                            <div class="form-group">
                                <label>Quantity label (Seller)</label>
                                {{ Form::text('quantity_label', null, ['class' => 'form-control', 'placeholder' => 'e.g. Inventory, Room, Spaces per session']) }}
                                <small class="form-text text-muted">The display name for the seller to enter a quantity for the listing. e.g. a seller listing rooms for rent would see "Rooms", a seller selling products would see "Quantity".</small>
                            </div>

                            <div class="form-group">
                                <label>Duration <small class="text-muted">(Book a date widget only)</small></label>
                                {{ Form::select('duration_name', ['day' => 'Day', 'night' => 'Night'], null, ['placeholder' => 'Select...', 'class' => 'form-control']) }}
                                <small class="form-text text-muted"></small>
                            </div>
											<hr />	
											<div class="card card-body bg-light">
    <h5 class="card-title">Buyer labels</h5>

                            <div class="form-group">
                                <label>Per label (Buyer)</label>
								<div class="input-group mb-0">
								  <div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon3">Per</span>
								  </div>
								  {{ Form::text('per_label_buyer', null, ['class' => 'form-control', 'placeholder' => 'session']) }}
								</div>
								<div class="form-check m-0">
                                {{ Form::checkbox('can_seller_enter_per_label', true, null, ['class' => 'form-check-input', 'id' => 'can_seller_enter_per_label']) }}
									<label class="form-check-label small" for="can_seller_enter_per_label">Can seller enter own label</label>
								</div>
                                <small class="form-text text-muted">Use this to overwrite the label displayed to the user e.g. session, day, night</small>
                            </div>
							
                            <div class="form-group">
                                <label>Quantity label (Buyer) </label>
                                {{ Form::text('quantity_label_buyer', null, ['class' => 'form-control', 'placeholder' => 'e.g. Inventory, Room, Spaces per session']) }}
                                <small class="form-text text-muted">The buyer sees this for the quantity label.</small>
                            </div>
                            </div>
<hr />			


                            <div class="form-check">
                                {{ Form::checkbox('can_add_pricing', true, null, ['class' => 'form-check-input', 'id' => 'can_add_pricing']) }}
                                <label class="form-check-label" for="can_add_pricing">Can add pricing</label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Allow sellers to add a price to their listing."><i class="fa fa-info-circle"></i></a>
                            </div>

                            <div class="form-check">
                                {{ Form::checkbox('can_accept_payments', true, null, ['class' => 'form-check-input', 'id' => 'can_accept_payments']) }}
                                <label class="form-check-label" for="can_accept_payments">Can accept payments</label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Turn this off if you only want sellers to list their item and not accept payments."><i class="fa fa-info-circle"></i></a>
                            </div>

                            <div class="form-check">
                                {{ Form::checkbox('can_add_variants', true, null, ['class' => 'form-check-input', 'id' => 'can_add_variants']) }}
                                <label class="form-check-label" for="can_add_variants">Can add variations of the item <small class="text-muted">(Buy widget only)</small></label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Let sellers add variations e.g. colors, sizes for their product"><i class="fa fa-info-circle"></i></a>
                            </div>

                            <div class="form-check">
                                {{ Form::checkbox('can_add_shipping', true, null, ['class' => 'form-check-input', 'id' => 'can_add_shipping']) }}
                                <label class="form-check-label" for="can_add_shipping">Can add shipping fee <small class="text-muted">(Buy widget only)</small></label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Let sellers add shipping fees for their product"><i class="fa fa-info-circle"></i></a>
                            </div>

                            <div class="form-check">
                                {{ Form::checkbox('can_add_additional_pricing', true, null, ['class' => 'form-check-input', 'id' => 'can_add_additional_pricing']) }}
                                <label class="form-check-label" for="can_add_additional_pricing">Can add additional services/prices</label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Let sellers add service fees or addition options with their listing"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="form-check">
                                {{ Form::checkbox('requires_shipping_address', true, null, ['class' => 'form-check-input', 'id' => 'requires_shipping_address']) }}
                                <label class="form-check-label" for="requires_shipping_address">Require Shipping Address</label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Ask the user to provide shipping address"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="form-check">
                                {{ Form::checkbox('requires_billing_address', true, null, ['class' => 'form-check-input', 'id' => 'requires_billing_address']) }}
                                <label class="form-check-label" for="requires_billing_address">Require Billing Address</label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Ask the user to provide billing address"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="form-check">
                                {{ Form::checkbox('can_list_multiple_services', true, null, ['class' => 'form-check-input', 'id' => 'can_list_multiple_services']) }}
                                <label class="form-check-label" for="can_list_multiple_services">Can list multiple services <small class="text-muted">(Book a time widget only)</small></label>
                                <a href="" class="small text-muted" data-toggle="tooltip" data-placement="top" title="Can the user list multiple services"><i class="fa fa-info-circle"></i></a>
                            </div>


                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
@endsection
