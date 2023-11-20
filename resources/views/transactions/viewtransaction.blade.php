@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Transactions') }}</div>

                <div class="card-body">
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
                                        <th scope="col">{{__('Amount + Vat')}}</th>
                                        <th scope="col">{{__('Status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>{{ $transactions->name }}</td>
                                            <td>{{ number_format($transactions->amount, 2) }}</td>
                                            <td>{{ $transactions->dueon }}</td>
                                            <td>{{ $transactions->vat }}%</td>
                                            <td>{{ $transactions->vat_included == 1 ? 'Yes' : 'No' }}</td>
                                            <td>{{ $transactions->vat_included == 1 ? number_format($transactions->withvat, 2) : number_format($transactions->amount, 2) }}</td>
                                            <td>{{ $transactions->status }}</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <hr>
            <br/>
            <div class="card">
                <div class="card-header">{{ __('Payments') }}</div>

                <div class="card-body">
                    <div class="container">
                        <div class="table-responsive">
                            @php $totalAmount = 0; @endphp
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">{{__('Date')}}</th>
                                        <th scope="col">{{__('Amount')}}</th>
                                        <th xcope="col">{{__('Details')}}</th>
                                        <th xcope="col">{{__('Action')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($payments as $payment)
                                            @php $totalAmount += $payment->amount; @endphp
                                            <tr>
                                                <td>{{ $payment->date }}</td>
                                                <td>{{ number_format($payment->amount, 2) }}</td>
                                                <td>{{ $payment->details }}</td>
                                                <td>
                                                    <form action="{{route('payment.destroy', $payment->id)}}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger rounded-start" style="margin-right: 5px;">{{__('Delete')}}</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th class="text-right" style="text-align: right;">{{__('Total')}}</th>
                                            <th colspan="3"><strong>{{number_format($totalAmount, 2)}}</strong></th>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <span>{{session('success')}}</span>
                </div>
            @endif

            @if(auth()->user()->role == 'admin')
            <div class="card text-start">
              <div class="card-body">
                <details>
                    <summary>New Payment</summary>
                    <form action="{{route('payment.store')}}" method="POST">
                        @csrf
                        <div class="d-flex gap-3 mt-3 grid-2">
                            <div class="col pr-2 flex-fill">
                                <div class="mb-3">
                                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Amount" aria-describedby="helpId">
                                </div>
                            </div>
                            <div class="col pl-2 flex-fill">
                                <div class="mb-3 ml-2">
                                    <input type="date" name="date" id="date" class="form-control" placeholder="Date('Y-m-d')" aria-describedby="helpId">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <textarea name="details" id="details" cols="30" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col mt-3 flex-sm-fill">
                            <div class="mb-3">
                                <input class="btn btn-primary d-block btn-block ml-auto" type="submit" value="{{__('Submit')}}">
                            </div>
                        </div>
                        <input type="hidden" name="transaction" value="{{$transactions->id}}">
                    </form>
                </details>
                
                
              </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
