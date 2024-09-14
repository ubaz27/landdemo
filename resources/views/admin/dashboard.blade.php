@extends('admin.layout.master')
{{-- @extends('admin.asset.scripts') --}}

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush


@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            {{-- Welcome {{ Auth::admin()->name }} --}}
        </div>

    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                <div class="col-md-2 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Lands</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    @foreach ($land_no as $item)
                                        <h3 class="mb-2">{{ $item->no_lands }}</h3>
                                    @endforeach

                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            {{-- <span>+3.3%</span> --}}
                                            {{-- <i data-lucide="arrow-up" class="icon-sm mb-1"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Members</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    @foreach ($member_no as $item)
                                        <h3 class="mb-2">{{ $item->no_members }}</h3>
                                    @endforeach
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-danger">

                                            {{-- <i data-lucide="arrow-down" class="mdi mdi-account"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Plots</h6>
                                <div class="dropdown mb-2">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    @foreach ($plot_no as $item)
                                        <h3 class="mb-2">{{ $item->no_plots }}</h3>
                                    @endforeach
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">

                                            {{-- <i data-lucide="arrow-up" class="mdi mdi-crop-landscape"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Allocated Plots</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    @foreach ($allocated_plot_no as $item)
                                        <h3 class="mb-2">{{ $item->no_plots }}</h3>
                                    @endforeach
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">

                                            {{-- <i data-lucide="arrow-up" class="icon-sm mb-1"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Agents</h6>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    @foreach ($agent_no as $item)
                                        <h3 class="mb-2">{{ $item->no_agents }}</h3>
                                    @endforeach
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">

                                            {{-- <i data-lucide="arrow-up" class="icon-sm mb-1"></i> --}}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->





    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Available Plots</h6>
                        {{-- <div class="dropdown mb-2">
                            <button class="btn p-0" type="button" id="dropdownMenuButton7" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-lucide="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton7">
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i data-lucide="eye"
                                        class="icon-sm me-2"></i> <span class="">View</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                        data-lucide="edit-2" class="icon-sm me-2"></i> <span class="">Edit</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                        data-lucide="trash" class="icon-sm me-2"></i> <span class="">Delete</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                        data-lucide="printer" class="icon-sm me-2"></i> <span
                                        class="">Print</span></a>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                        data-lucide="download" class="icon-sm me-2"></i> <span
                                        class="">Download</span></a>
                            </div>
                        </div> --}}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dataTableExample">
                            <thead>
                                <tr>
                                    <th class="pt-0">S/N</th>
                                    <th class="pt-0">Land Name</th>
                                    <th class="pt-0">Plots No</th>
                                    <th class="pt-0">Dimenssion</th>
                                    <th class="pt-0">Cost</th>
                                    <th class="pt-0">Assign</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($available_plots as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->land_name }}</td>
                                        <td>{{ $item->plot_no }}</td>
                                        <td>{{ $item->dimension }}</td>
                                        <td>N {{ $item->Number }}</td>
                                        <td><a href="">Assign</a></td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- row -->
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
