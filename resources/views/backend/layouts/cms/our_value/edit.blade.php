@extends('backend.app')

@section('title', 'Our Value Section')

@section('content')
<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <div class="flex-wrap container-fluid d-flex flex-stack flex-sm-nowrap">
        <!--begin::Info-->
        <div class="flex-wrap d-flex flex-column align-items-start justify-content-center me-2">
            <!--begin::Title-->
            <h1 class="my-1 text-dark fw-bold fs-2">
                Dashboard <small class="text-muted fs-6 fw-normal ms-1"></small>
            </h1>
            <!--end::Title-->

            <!--begin::Breadcrumb-->
            <ul class="my-1 breadcrumb fw-semibold fs-base">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
                        Home </a>
                </li>

                <li class="breadcrumb-item text-muted"> Our Value Section </li>

            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Info-->
    </div>
</div>
<!--end::Toolbar-->

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-4 card-style">
                <div class="card card-body">
                    <form method="POST" action="{{ route('admin.our_value.update', $our_value->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mt-4 input-style-1">
                            <label for="title">Title:</label>
                            <input type="text" placeholder="Enter title" id="title"
                                class="form-control @error('title') is-invalid @enderror" name="title"
                                value="{{ $our_value->title ?? old('title') }}" />
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mt-4 input-style-1">
                            <label for="sub_title">Sub Title:</label>
                            <input type="text" placeholder="Enter sub title" id="sub_title"
                                class="form-control @error('sub_title') is-invalid @enderror" name="sub_title"
                                value="{{ $our_value->sub_title ?? old('sub_title') }}" />
                            @error('sub_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mt-4 input-style-1">
                            <label for="image">Image:</label>

                            <input type="file" placeholder="Enter Image" id="image"
                                class="dropify form-control @error('image') is-invalid @enderror" name="image"
                                data-default-file="{{ asset( $our_value->image_url ?? 'backend/images/placeholder/image_placeholder.png') }}" />

                            @error('image')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mt-4 col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.our_value.index') }}" class="btn btn-danger me-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection