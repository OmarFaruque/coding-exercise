@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Report') }}</div>

                <div class="card-body">
                    <div class="container">
                        <div class="table-responsive">
                            <form class="d-flex" action="{{route('transaction-report')}}" method="post">
                                @csrf
                                <div class="col p-2 flex-fill">
                                    <div class="mb-3">
                                        <label for="date">{{ __('Start') }}</label>
                                        <input type="date" name="start" id="start" class="form-control" value="{{old('start')}}" aria-describedby="helpId">
                                    </div>
                                </div>
                                <div class="col p-2 flex-fill">
                                    <div class="mb-3">
                                        <label for="date">{{ __('End') }}</label>
                                        <input type="date" name="end" id="end" class="form-control" aria-describedby="helpId" value="{{old('end')}}}">
                                    </div>
                                </div>
                                <div class="col p-2 flex-fill">
                                    <div class="mb-3">
                                        <input type="submit" name="Submit" class="btn btn-primary mt-4" aria-describedby="helpId">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card mt-3">
                <div class="card-body">
                    <div class="container">
                        <div class="table-responsive">
                            @if(isset($reports))
                                    <div class="table-responsive-sm">
                                        <table class="table table-striped
                                        table-hover	
                                        table-borderless
                                        table-primary
                                        align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{__('Month')}}</th>
                                                    <th>{{__('Year')}}</th>
                                                    <th>{{__('Paid')}}</th>
                                                    <th>{{__('Outstanding')}}</th>
                                                    <th>{{__('Overdue')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody class="table-group-divider">
                                                    @foreach($reports as $report)
                                                        <tr>
                                                            <td>{{date("F", mktime(0, 0, 0, $report->month, 10));}}</td>
                                                            <td>{{$report->year}}</td>
                                                            <td>{{ number_format($report->paid, 2) }}</td>
                                                            <td>{{ number_format($report->outstanding, 2)}}</td>
                                                            <td>{{number_format($report->overdue, 2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    
                                                </tfoot>
                                        </table>
                                    </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
