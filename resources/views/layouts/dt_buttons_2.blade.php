
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="#" id="EditBtn" data-id="{{ $id }}"><i class="fa fa-pencil"></i> Edit</a>
    @if(request()->is('booking'))
      <a class="dropdown-item PrintReceiptBtn" href="#" data-id="{{ $id }}"><i class="fa fa-print"></i> Print Receipt</a>
    @endif
    <a class="dropdown-item" href="#" id="DeleteBtn" data-id="{{ $id }}"><i class="fa fa-trash"></i> Delete</a>

  </div>
</div>

