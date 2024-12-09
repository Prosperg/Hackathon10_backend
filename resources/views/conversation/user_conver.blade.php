@extends('layouts.add')
<link rel="stylesheet" href="{{asset('dicts/bootstrap.min.css')}}" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

@section('pagetitle')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"></h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    
                    <li class="breadcrumb-item active"></li>
                </ol>
            </div>

        </div>
    </div>
</div>  
@section('content')
<div class="d-lg-flex">
    @include('conversation.users',["users"=>$users])
    
    
</div>
@endsection
@section('js')
<script src="{{asset('dicts/bootstrap.min.js')}}" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
@endsection

 