@extends('admin.layout.master')
@extends('admin.asset.scripts')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Agent Report Summary</h4>
        </div>

        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agent Report Payment</h4>
                    <hr>
                    {{-- <p class="text-muted mb-3">Read the <a href="https://jqueryvalidation.org/" target="_blank"> Official jQuery
                            Validation Documentation </a>for a full list of instructions and other options.</p> --}}
                    <form id="signupForm" action="{{ route('admin.AgentStatementExcel') }}" Method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="ageSelect" class="form-label">Agent Name (Record)</label> <span
                                    class="text-danger">(required)</span>
                                <select class="js-example-basic-single form-select" name="agent_id" id="agent_id">
                                    <option selected disabled>Select Agent Record</option>
                                    @foreach ($agents as $item)
                                        <option value="{{ $item->id }}">{{ $item->name . ' ' . $item->phone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="confirm_password" class="form-label">From</label> <span
                                    class="text-danger">(required)</span>
                                <input id="sdate" class="form-control" name="sdate" type="date">
                            </div>
                            <div class="col-md-3">
                                <label for="confirm_password" class="form-label">To</label> <span
                                    class="text-danger">(required)</span>
                                <input id="edate" class="form-control" name="edate" type="date">
                            </div>



                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" name="type" value="excel"
                                    class="btn btn-outline-success float-right"><i class="fas fa-print"></i>
                                    Generate Excel</button>

                                <button type="submit" name="type" value="pdf"
                                    class="btn btn-outline-danger float-right mr-2"><i class="fas fa-print"></i>
                                    Generate PDF</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection
