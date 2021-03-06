@extends('layouts.main')

@section('content')
<div class="container">
    <div>
        @foreach($arts as $art)
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-img">
                                <a href="#"><img class="avatar-pic" src="{{ $art->user->avatar_url }}"/></a>
                            </div>
                            <div class="card-header-text">
                                <a href="#">{{ $art->user->name }}</a>
                            </div>
                            <div class="card-header-date">
                                <span>{{ $art->created_at->toDateString() }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{$art->title}}</h5>
                            <img src="{{ Storage::cloud()->url($art->downloads->where('featured', 1)->first()->url) }}">
                            @if($art->description)
                                <p class="card-text">{{$art->description}}</p>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="card-footer-icons">
                                @svg('heart', 'heart-icon')
                            </div>
                            <div class="card-footer-report">
                                <button type="button" v-on:click="open_report_modal('{{ encrypt($art->id) }}')" class="btn btn-outline-danger">Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        @endforeach
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form method="POST" action="{{ route('add.report', ['type' => 'art']) }}">
                {!! csrf_field() !!}
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report This Art</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea class="form-control" name="body" placeholder="Please describe why the art should not be on the website..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button name="action" type="submit" class="btn btn-primary">Submit</button>
            </div>
            <input type="hidden" name="_q" v-bind:value="id">
        </form>
        </div>
    </div>
</div>




@endsection