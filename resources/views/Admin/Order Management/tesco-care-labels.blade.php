@extends('layouts.Admin.app')
@if (Auth::guard("web")->user()) 
    @section('content')
    <link rel="stylesheet" href="{{ asset('css/tesco-care-orders.css') }}">
    <div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <h3><i class="ion-ios-browsers-outline"></i>Create Order</h3>
        <hr>
        <div class="row">
        <div class="col-md-12">
            <h4>Order Details</h4>
        </div>
        <div class="col-md-2">
            <div class="form-group"><label>Tesco PO Number</label><input id="" value="" type="text" class="form-control input-sm"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group"><label>EQOS Supplier Number</label><input id="" value="" type="text" class="form-control input-sm"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group"><label>Option ID</label><input id="" value="" type="text" class="form-control input-sm"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group"><label>UK Style Number</label><input id="" value="" type="text" class="form-control input-sm"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group"><label>Fabric Batch Code</label><input id="" value="" type="text" class="form-control input-sm"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group"><label>Leather & Suede ID</label><input id="" value="" type="text" class="form-control input-sm"></div>
        </div>

        <div class="col-md-2">
            <div class="form-group"><label>Country of Origin</label><input id="" value="" type="select" class="form-control input-sm"></div>
        </div>
        <div class="col-md-2">
            <div class="form-group"><label style="visibility:hidden">Dont Show</label>
            <div class="checkbox">
                <label>
                <input type="checkbox"> Include Button Sew Space
                </label>
            </div>
            </div>
        </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-12">
            <h4>Order Quantity</h4>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>UK</th>
                    <th>EUR</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Chest</th>
                    <th>UK EAN</th>
                    <th>CE EAN</th>
                    <th>Quantity <small>(Units)</small></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                <tr>
                    <td>Tiny Baby</td>
                    <td>18-24M</td>
                    <td>44cm/17 <sup>1/4</sup> In</td>
                    <td>12.5kg/28lbs</td>
                    <td>52.5cm/20<sup>5/8</sup>in</td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="UK EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="CE EAN Number"></div></td>
                    <td><div class="form-group"><input class="form-control input-sm" placeholder="Quantity"></div></td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-12">
            <h4>Garment Component & Fibres</h4>
        </div>
        <div class="col-md-2">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                <label>Garment Component 1 </label>
                <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                <label>Garment Component 1 </label>
                <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                <label>Garment Component 1 </label>
                <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                <label>Garment Component 1 </label>
                <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                <label>Garment Component 1 </label>
                <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                <label>Garment Component 1 </label>
                <select class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <select class="form-control input-sm">
                    <option selected>Select Fiber</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control input-sm">
                    <div class="input-group-addon">%</div>
                </div>
                </div>
            </div>

            </div>
        </div>
        <div class="col-xs-12">
            <div class="align-items">
            <div class="checkbox">
                <label>
                <input type="checkbox"> Exclusive of Trims
                </label>
            </div>
            <div class="checkbox">
                <label>
                <input type="checkbox"> Contains Non Textile Parts
                </label>
            </div>
            <div class="checkbox">
                <label>
                <input type="checkbox"> Part of Set
                </label>
            </div>

            </div>

        </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-12">
            <h4>Care Instructions</h4>
        </div>
        <div class="col-md-2">
            <div class="form-group">
            <label>Select Wash Instructions</label>
            <select class="form-control">
                <option selected>select</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
            <label>Select Bleach Instructions</label>
            <select class="form-control">
                <option selected>select</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
            <label>Select Dry Instructions</label>
            <select class="form-control">
                <option selected>select</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
            <label>Select Iron Instructions</label>
            <select class="form-control">
                <option selected>select</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
            <label>Select Dry Clean Instructions</label>
            <select class="form-control">
                <option selected>select</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
            </div>
        </div>
        </div>
        <hr>
        <div class="row">
        <div class="col-md-8">
            <div class="row">
            <div class="col-md-12">
                <h4>Additional Instructions</h4>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label>Additional Instructions 1</label>
                <select class="form-control">
                    <option selected>select</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label>Additional Instructions 2</label>
                <select class="form-control">
                    <option selected>select</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label>Additional Instructions 3</label>
                <select class="form-control">
                    <option selected>select</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            </div>
            <hr>
        </div>
        <div class="col-md-4">
            <div class="row">
            <div class="col-md-12">
                <h4>Additional Instructions</h4>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label>Select Kids Warning Statement </label>
                <select class="form-control">
                    <option selected>select</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                </select>
                </div>
            </div>
            </div>
            <hr>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="alert alert-danger" role="alert">These 3 fields are required only if the size chart selected is “Baby Sleeping Bags”</div>
        </div>
        <div class="col-xs-12">
            <div class="align-items">
            <div class="checkbox">
                <label>
                <input type="checkbox"> Conforms to BS EN 16781:2018
                </label>
            </div>
            <div class="checkbox">
                <label>
                <input type="checkbox"> <img style="height:24px" src="https://ibena.de/assets/images/7/375px-Waschen_30ss-svg-5af4f91a.png">
                </label>
            </div>
            <div class="form-group form-inline">
                <label>TOG</label>
                <input type="text" class="form-control input-sm" placeholder="XXXX">
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="bottom-bar">
        <div class="modal-footer"> 
            <span class="pagename pull-left hidden-xs">Please save or submit changes before closing
                page.</span> 
                <button class="btn btn-xs btn-success save-btn" type="button" onclick="saveTescoOrder()"
                data-insert="true"><i class="fa fa-edit" aria-hidden="true"></i> Save &amp; Continue</button> 

                <button class="btn btn-xs btn-warning submit-btn" type="submit"><i class="fa fa-floppy-o"
                    aria-hidden="true"></i> Submit</button> 

                <button class="btn btn-xs btn-danger cancel-btn" type="button"
                data-toggle="modal" data-target="#cancelOrderModal" ><i class="fa fa-times"
                    aria-hidden="true"></i> Cancel</button>
            
                <button class="btn btn-xs btn-danger reload-btn" type="button"
                onclick="reload_back()"><i class=" fa fa-refresh" aria-hidden="true"></i> Reload</button> 
        </div>
    </div>
    </div>


    
    @endsection 
@else
	<script type="text/javascript">
		window.location = "{{ url('/admin') }}";

	</script>
@endif 
@section('jqueryIncludes') 
	<script src="{{ asset('js/common/tesco-care-label-orders.js') }}"></script>
    <!-- Financial year selection script -->
    <script>
        /* Show logo on left side */
        $('.logo').html('Orders');
	</script>
			

@endsection
