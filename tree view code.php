<div class="col-md-6 modules_div">
  <div class="row">
      {!! Form::label('role_permissions', 'Role Permissions', ['class' => ' form_label']) !!}
  </div>
  @foreach($modules_tree as $key => $sub_mod)
  <li class="treeview">
    <a href="#"><i class="fa fa-link"></i> <span>{{ $sub_mod->module_name }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      @foreach($sub_mod->second as $key1 => $sub_mod1)
        <li class=""><a href="#">{{$sub_mod1->module_name}}</a></li>
        <ul class="treeview-menu">
        @foreach($sub_mod1->third as $key2 => $sub_mod2)
          <li class=""><a href="#">{{$sub_mod2->module_name}}</a></li>
        @endforeach
      </ul>
      @endforeach
    </ul>
  </li>
@endforeach
</div>
