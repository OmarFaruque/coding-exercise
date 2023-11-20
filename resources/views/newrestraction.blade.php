@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

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

                    <div class="container">
                        <form method="POST" action="{{ route('new-transactions') }}">
                            @csrf
                            {{-- Amount --}}
                            <div class="mb-3 row">
                                <label for="amount" class="col-4 col-form-label">{{__('Amount')}}</label>
                                <div class="col-8">
                                    <input type="number" step="1" min="0" class="form-control" name="amount" id="amount" placeholder="0">
                                </div>
                            </div>

                            {{-- Payer --}}
                            <div class="mb-3 row">
                                <label for="payer" class="col-4 col-form-label">{{__('Payer')}}</label>
                                <div class="col-8">
                                    <select class="form-select form-select-md" name="payer" id="payer">
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>    
                                </div>
                            </div>


                            {{-- Due on --}}
                            <div class="mb-3 row">
                                <label for="amount" class="col-4 col-form-label">{{__('Due On')}}</label>
                                <div class="col-8">
                                    <input type="date" class="form-control" name="dueon" id="dueon" placeholder="0">
                                </div>
                            </div>

                            {{-- Vat --}}
                            <div class="mb-3 row">
                                <label for="amount" class="col-4 col-form-label">{{__('Vat')}}</label>
                                <div class="col-8">
                                    <div class="input-group mb-0">
                                        <input type="number" class="form-control" name="vat" placeholder="0" aria-label="vat" aria-describedby="basic-addon1">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon1">%</span>
                                          </div>
                                      </div>                                      
                                </div>
                            </div>

                            {{-- Include Vat ? --}}
                            <div class="mb-3 row">
                                <label for="amount" class="col-4 col-form-label">{{__('Is VAT inclusive')}}</label>
                                <div class="col-8">
                                    <div class="input-group mb-0">
                                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                            <input type="radio" class="btn-check" name="vat_included" value="1" id="vat_included1" autocomplete="off" checked>
                                            <label class="btn btn-outline-primary" for="vat_included1">{{__('Yes')}}</label>
                                          
                                            <input type="radio" class="btn-check" name="vat_included" value="0" id="vat_included2" autocomplete="off">
                                            <label class="btn btn-outline-primary" for="vat_included2">{{ __('No') }}</label>                                          
                                            
                                          </div>  
                                    </div>                                      
                                </div>
                            </div>

                            <div class="mb-3 mt-5 row">
                                <div class="offset-sm-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
