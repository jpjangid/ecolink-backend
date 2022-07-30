@extends('layouts.main')

@section('title', 'View Form')

@section('content')
<div class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">View Form</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/forms/list') }}" class="btn btn-info mt-o" style="float: right;">Back</a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div id="loader"></div>
    <div class="card">
        <div class="card-body">
                <div class="row">
                    @foreach(json_decode($form_datas->form_data) as $form_data)
                        @if($form_data->type != "file") 
                        <div class="col-md-6">
                                <label for="">{{ $form_data->name }} :</label><p>
                                @if(gettype($form_data->value) == "array")
                                    {{ implode(', ',$form_data->value) }}
                                @else
                                    {{ $form_data->value }}</p>
                                @endif
                        </div>
                        @else
                        <div class="col-md-12">
                            <label for="">{{ $form_data->name }}</p>
                            <div class="row">
                                @if(!empty($form_data->value))
                                @foreach($form_data->value as $image)
                                <div class="col-md-4 text-center ml-2 container">
                                    <img src="{{$image}}" height="250" width="250"/> 
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    
                        @endif
                    @endforeach
                </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/validations/pages/editpagerules.js') }}"></script>
<script type=text/javascript>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result).width(150).height(150);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection