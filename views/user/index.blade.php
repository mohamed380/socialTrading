@extends('user.layout.general')
@section('mainSliderContainer')


@include('user.projectsTemplate',['projects'=>$projects])
@endsection

