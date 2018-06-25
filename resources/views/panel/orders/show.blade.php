@extends('panel::layouts.master')

@section('content')
    <a href="{{ route('panel.orders.index') }}" class="mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> back</a>

    <div class="row mb-3">
        <div class="col-sm-8">
            <h2  class="mt-xxs">Viewing order #{{$order->hash}}</h2>
        </div>
        <div class="col-sm-4">

        </div>

    </div>

    <div class="row">

        <div class="col-sm-12">

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="row">Listing</th>
                        <td>{{$order->listing->title}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Status</th>
                        <td><span class="badge badge-warning">{{$order->status}}</span></td>
                    </tr>
                    <tr>
                        <th scope="row">Amount</th>
                        <td>{{$order->amount}} {{$order->currency}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Service fee</th>
                        <td>{{$order->service_fee}} {{$order->currency}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Buyer</th>
                        <td>{{$order->user->name}} ({{$order->user->email}})<br />
                            <pre>{!! array_to_string($order->customer_details) !!}</pre>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Seller</th>
                        <td>{{$order->listing->user->name}} ({{$order->listing->user->email}}) @if($order->payment_gateway)- <a href="https://dashboard.stripe.com/{{$order->payment_gateway->gateway_id}}/payments/{{$order->authorization_id}}" target="_blank">{{$order->payment_gateway->gateway_id}}</a>@endif</td>
                    </tr>
                    <tr>
                        <th scope="row">Date&nbsp;Placed</th>
                        <td>{{$order->created_at->toDayDateTimeString()}}</td>
                    </tr>
                </tbody>
            </table>

    </div>

@endsection
