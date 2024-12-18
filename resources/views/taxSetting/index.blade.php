@extends('layouts.app')
@section('content')
   
    <div class="container py-5">
        {{-- <section class="button mb-4">
            <a href="{{ asset('taxSetting/create') }}" class="btn btn-info text-capitalize">Add TaxSetting</a>
        </section> --}}
        <div class="row">
            <div class="col-md-9 m-auto">
                <div class="card">
                    <div class="card-header bg-defult">
                        <div class="card-title">
                            <h2 class="card-title">
                                <button type="button" class="btn bg-navy text-capitalize mr-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create TaxSetting"data-toggle="modal" data-target="#NewTaxModal"> 
                                    <i class="fa-solid fa-circle-plus mr-2"></i>
                                    Add
                                </button>
                                TaxSetting List
                            </h2>
                        </div>
                        <a class="btn btn-sm bg-navy float-right text-capitalize" href="/taxSetting/trash"><i class="fa-solid fa-recycle mr-2"></i>View Trash</a>
                        <button class="btn btn-sm bg-maroon float-right text-capitalize mr-3" id="DeleteAllBtn">
                            <i class="fa-solid fa-trash-can mr-2"></i>
                            Delete All
                        </button>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap ListTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Percent</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                {{-- @foreach ($TaxSettings as $TaxSetting)
                                    <tr>
                                        <td>{{ $TaxSetting->Name }}</td>
                                        <td>{{ $TaxSetting->Percent }}</td>
                                        <td>
                                            @if($TaxSetting->Status)
                                            <b class="text-success fs-6">Active</b>      
                                            @else <b class="text-danger fs-6">Deactive</b> @endif
                                        </td>
                                        <td class="d-flex">
                                            <button class="EditBtn" value="{{ $TaxSetting->id }}" title="Edit" ><i class="fa-regular fa-pen-to-square mr-3 text-orange"></i>
                                            </button>

                                            <button class="DeleteBtn" value="{{ $TaxSetting->id }}" title="Delete">
                                                <i class="fa-regular fa-trash-can mr-3 text-danger"></i>
                                            </button>
                                            
                                            <!-- {{ Form::open(array('url' => '/taxSetting/'.$TaxSetting->id,'method' => 'DELETE')) }}
                                                <button class="" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                                    <i class="fa-regular fa-trash-can mr-3 text-danger"></i>
                                                </button>
                                            {{ Form::close() }}  -->
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="NewTaxModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add A New  TaxSetting</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('url' => '/taxSetting', 'method' => 'post','class' => 'form-horizantal','id' => 'NewTaxForm', 'files' => true)) }}
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="Name" class="form-label col-md-3">Name:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="Name" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Percent" class="form-label col-md-3">Percent:</label>
                                    <div class="col-md-8">
                                        <input type="number" name="Percent" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Status" class="form-label col-md-3">Status:</label>
                                    <div class="col-md-8">
                                        <div class="form-check form-check-inline ml-1">
                                            <input type="radio" class="form-check-input" name="Status" value="1">
                                            <label for="" class="form-check-label">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline ml-4">
                                            <input type="radio" class="form-check-input" name="Status" value="0">
                                            <label for="" class="form-check-label">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default text-capitalize" id="ResetBtnForm">Reset</button>
                                <button type="button" name="submit" type="submit" class="btn bg-navy text-capitalize" id="SubmitBtn">submit</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="EditTaxModal" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-navy text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-edit mr-2"></i>
                            Update Tax Setting
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(array('method' => 'PATCH','class' => 'form-horizantal','id'=>'EditTaxForm', 'files' => true)) }}
                            <input type="hidden" name="ID" id="IDEdit">
                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group row align-items-center mb-4">
                                        <label for="Name" class="form-label col-md-3">
                                            <i class="fas fa-tag mr-1"></i>
                                            Name
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" id="NameEdit" name="Name" class="form-control" 
                                                   placeholder="Enter tax name"> 
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center mb-4">
                                        <label for="Percent" class="form-label col-md-3">
                                            <i class="fas fa-percent mr-1"></i>
                                            Percent
                                        </label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input type="number" id="PercentEdit" name="Percent" 
                                                       class="form-control" placeholder="Enter percentage">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label for="Status" class="form-label col-md-3">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                            Status
                                        </label>
                                        <div class="col-md-9">
                                            <select name="Status" id="StatusEdit" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i>
                                    Cancel
                                </button>
                                <button type="button" name="submit" class="btn bg-navy text-capitalize" id="UpdateBtn">
                                    <i class="fas fa-save mr-1"></i>
                                    Update Changes
                                </button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/custom-js/taxSetting.js') }}"></script>
@endsection
