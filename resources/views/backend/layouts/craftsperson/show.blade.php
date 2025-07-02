@extends('backend.app')
@push('style')
    <style>
        .order-track {
            margin-top: 2rem;
            padding: 0 1rem;
            border-top: 1px dashed #2c3e50;
            padding-top: 2.5rem;
            display: flex;
            flex-direction: column;
        }

        .order-track-step {
            display: flex;
            height: 6rem;
        }

        .order-track-step:last-child {
            overflow: hidden;
            height: 4rem;
        }

        .order-track-step:last-child .order-track-status span:last-of-type {
            display: none;
        }

        .order-track-status {
            margin-right: 1.5rem;
            position: relative;
        }

        .order-track-status-dot {
            display: block;
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 50%;
            background: #fb7185;
        }

        .order-track-status-line {
            display: block;
            margin: 0 auto;
            width: 2px;
            height: 6rem;
            background: #fb7185;
        }

        .order-track-text-stat {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 3px;
        }

        .order-track-text-sub {
            font-size: .875rem;
            font-weight: 300;
        }

        .order-track {
            transition: all 0.3s height 0.3s;
            transform-origin: top center;
        }
    </style>
@endpush

@section('title', 'Order Page')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-4 order-0">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="color: rgb(11 123 247) !important">Craftsperson Information
                            
                        </h4>
                        <div class="items-center mb-4">
                            <img src="{{ asset($data->avatar ?? 'backend/images/profile.jpeg') }}" alt="Customer Image"
                                class="rounded mb-2" style="width: 3rem; height: 3rem;">
                            <div>
                                <p class="p-0 m-0 font-bold"
                                    style="font-size: 1.2rem; font-weight: bold; color:#004372 !important">
                                    {{ $data->name ?? 'N/A' }}
                                </p>
                                <p>{{ $data->craftsperson->category->name ?? 'N/A' }}</p>
                                <p class="p-0 m-0">Email: <span class="text-muted">{{ $data->email ?? 'N/A' }}</span> </p>
                                <p class="p-0 m-0">Phone: <span class="text-muted">{{ $data->phone ?? 'N/A' }}
                                    </span></p>
                                <p class="p-0 m-0"><span>Status: <span
                                            class="text-white badge @if ($data->status == 'active') badge-success @elseif($data->status == 'pending') badge-warning @endif) ">{{ $data->status ?? 'N/A' }}</span>
                                </p>

                                <p>Service Fee: ${{ $data->craftsperson->price ?? 'N/A' }}/h</p>

                                @php
                                    $document = $data->craftsperson->police_verification_document ?? 'N/A';
                                    $extension = pathinfo($document, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'gif',
                                        'bmp',
                                        'webp',
                                    ]);
                                @endphp

                                <p class="p-0">
                                    Police Verification Document:
                                    @if ($isImage)
                                        <img src="{{ asset($document) }}" alt="Police Verification Document" width="100">
                                    @elseif($document)
                                        <a href="{{ asset($document) }}" target="_blank" download="download">{{$document}}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>

                                @php
                                    $documentID = $data->craftsperson->craftsperson_id ?? 'N/A';
                                    $extension = pathinfo($documentID, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'gif',
                                        'bmp',
                                        'webp',
                                    ]);
                                @endphp
                                <p class="p-0">
                                    Craftsperson ID:
                                    @if ($isImage)
                                        <img src="{{ asset($documentID) }}" alt="Police Verification Document" width="100">
                                    @elseif($documentID)
                                        <a href="{{ asset($documentID) }}" target="_blank" download="download">{{$documentID}}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 order-0">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="color: rgb(11 123 247) !important">Craftsperson Address
                            <a href="{{ route('admin.craftsperson.index') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                        <div class="items-center mb-4">
                            @foreach ($data->addresses as $address)
                            <h2>Address {{ $loop->iteration }}</h2>
                            <div class="mb-4">
                                <p class="p-0 m-0 font-bold" style="font-size: 1.2rem; font-weight: bold;">

                                </p>
                                <p class="p-0 m-0">Type: <span class="text-muted">{{ $address->type }}</span>
                                </p>
                                <p class="p-0 m-0">Address Name: <span class="text-muted">{{ $address->type }}</span>
                                </p>
                                <p class="p-0 m-0">Street: <span class="text-muted">{{ $address->street }}</span>
                                </p>
                                <p class="p-0 m-0">Post Code: <span class="text-muted">{{ $address->post_code }}</span>
                                </p>
                                <p class="p-0 m-0">Apartment: <span class="text-muted">{{ $address->apartment }}</span>
                                </p>
                            </div>
                            
                            @endforeach
                        </div>
                    </div>
                </div>
                {{-- <br>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="color: rgb(11 123 247) !important">Shipping Information</h4>
                        <div class="items-center mb-4">
                            <div>
                                <p class="p-0 m-0 font-bold" style="font-size: 1.2rem; font-weight: bold;">
                                </p>
                                <p class="p-0 m-0">Full Name: <span class="text-muted"></span>
                                </p>
                                <p class="p-0 m-0">Phone: <span class="text-muted"> </span>
                                </p>
                                <p class="p-0 m-0">Email: <span class="text-muted"></span>
                                </p>
                                <p class="p-0 m-0">Country: <span class="text-muted"></span></p>
                                <p class="p-0 m-0">State: <span class="text-muted"></span></p>
                                <p class="p-0 m-0">Zipcode: <span class="text-muted"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="color: rgb(11 123 247) !important">Billing Information</h4>
                        <div class="items-center mb-4">
                            <div>
                                <p class="p-0 m-0">Invoice Number: <span class="text-muted"></span>
                                </p>
                                <p class="p-0 m-0">Amount: <span class="text-muted">
                                    </span> </p>
                                <p class="p-0 m-0">Quantity: <span class="text-muted"></span></p>
                                <p class="p-0 m-0">Total Amount: <span class="text-muted"></span>
                                </p>
                                <p class="p-0 m-0">Status: <span class="text-muted">

                                    </span></p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>



@endsection

@push('script')
@endpush
