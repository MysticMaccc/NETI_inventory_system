<section>
    <div class="pagetitle">
        <h1>Inventory</h1>
    </div>
    
    <section class="section">
        
            <div class="card">
            <div class="card-body row">
                <div class="col-md-12">
                    <h5 class="card-title">Manage Inventory</h5>
                </div>

                <div class="col-md-12">
                    <a href="{{route('products.new')}}" class="btn btn-outline-primary float-end">Create Inventory</a>
                    <x-notification-message />
                </div>

                <div class="col-md-4 offset-md-8 mt-5">
                     <input type="text" wire:model.live="search" class="form-control" placeholder="Search name, manufacturer, category, supplier...">
                </div>
                <div class="col-md-12 table-responsive mt-1" >
                            <table class="table table-hover table-striped">
                                    <thead>
                                            <tr>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total</th>
                                                    <th>Manufacturer</th>
                                                    <th @if(auth()->user()->usertype_id == "2")  hidden @endif>Department</th>
                                                    <th>Category</th>
                                                    <th>Supplier</th>
                                                    <th>Last Modified By</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product_data as $data)
                                                <tr>
                                                    <td>{{$data->name}}</td>
                                                    <td><textarea disabled>{{$data->description}}</textarea></td>
                                                    <td>{{$data->price}}</td>
                                                    <td>{{$data->quantity}} {{$data->unit->name}}</td>
                                                    <td>{{$data->price * $data->quantity}}</td>
                                                    <td>{{$data->manufacturer}}</td>
                                                    <td @if(auth()->user()->usertype_id==2) hidden @endif >{{$data->department->name}}</td>
                                                    <td>{{$data->category->name}}</td>
                                                    <td>{{$data->supplier->name}}</td>
                                                    <td>{{$data->LastModifiedBy}}</td>
                                                    <td>
                                                        @if ($data->quantity <= $data->low_stock_level)
                                                            <span class="badge bg-danger">Low Stock</span>
                                                        @else 
                                                            <span class="badge bg-success">On Stock</span>
                                                        @endif
                                                    </td>
                                                    <td style="width:150px;">
                                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ReplenishmentModal"
                                                        wire:click="getItem({{$data->id}})" title="Replenishment">
                                                            <i class="bi bi-plus-lg"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ConsumptionModal"
                                                        wire:click="getItem({{$data->id}})" title="Consumption">
                                                            <i class="bi bi-patch-minus"></i>
                                                        </button>
                                                        <a href="{{route('products.edit' , ['id' => $data->id])}}" class="btn btn-sm btn-info" title="edit"><i class="bi bi-gear-fill"></i></a>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                </div>
                <div class="col-md-12">
                    {{ $product_data->links("livewire::simple-bootstrap") }}
                </div>
                
            </div>
            </div>
            

            {{-- consumption modal --}}
            <div wire:ignore.self class="modal fade" id="ConsumptionModal" tabindex="-1" role="dialog" aria-labelledby="ConsumptionModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title mb-0" id="newCatgoryLabel">
                                Consumption for {{ $this->product_name }}
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                                <form class="row g-3" id="formConsumption" wire:submit.prevent="Consumption">
                                    @csrf
                                    <div class="col-md-12">
                                            <label class="form-label">Quantity in {{$this->unit}}</label>
                                            <input type="number" wire:model="quantity" class="form-control" min="1" max="{{$this->max_quantity}}">
                                    </div>
                                    <div class="col-md-12">
                                            <label class="form-label">Purpose</label>
                                            <textarea wire:model="purpose" class="form-control" cols="30" rows="5"></textarea>
                                    </div>
                                </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="formConsumption">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- consumption modal end--}}

            {{-- replenishment modal --}}
            <div wire:ignore.self class="modal fade" id="ReplenishmentModal" tabindex="-1" role="dialog" aria-labelledby="ReplenishmentModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title mb-0" id="newCatgoryLabel">
                                Replenishment for {{ $this->product_name }}
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                                <form class="row g-3" id="formReplenishment" wire:submit.prevent="Replenishment">
                                    @csrf
                                    <div class="col-md-12">
                                            <label class="form-label">Quantity in {{$this->unit}}</label>
                                            <input type="number" wire:model="quantity" class="form-control" min="1">
                                    </div>
                                    <div class="col-md-12">
                                            <label class="form-label">Description</label>
                                            <textarea wire:model="description" class="form-control" cols="30" rows="5"></textarea>
                                    </div>
                                </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="formReplenishment">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- replenishment modal end --}}

    </section>
</section>
