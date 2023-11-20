@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('All Transactions') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   

                    <div class="container">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">{{__('Name')}}</th>
                                        <th scope="col">{{__('Amount')}}</th>
                                        <th scope="col">{{__('Due On')}}</th>
                                        <th scope="col">{{__('Vat')}}</th>
                                        <th scope="col">{{__('Vat included')}}</th>
                                        <th scope="col">{{__('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $singleTransaction)
                                        <tr>
                                            <td>{{ $singleTransaction->name }}</td>
                                            <td>{{ $singleTransaction->amount }}</td>
                                            <td>{{ $singleTransaction->dueon }}</td>
                                            <td>{{ $singleTransaction->vat }}%</td>
                                            <td>{{ $singleTransaction->vat_included == 1 ? 'Yes' : 'No' }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @if(auth()->user()->role == 'admin')
                                                    <form action="{{route('delete-transaction', $singleTransaction->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger rounded-start" style="margin-right: 5px;">{{__('Delete')}}</button>
                                                    </form>
                                                    @endif
                                                    <a class="btn ml-2 btn-primary" href="{{route('view-transaction', $singleTransaction->id)}}">{{__('View')}}</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
