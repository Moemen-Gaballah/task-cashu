@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.sales')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.sales.index') }}">sales</a></li>
                <li class="active">@lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.sales.update', $sale->id) }}" method="post">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                            <div class="form-group">
                                <label>Sales</label>
                                <input type="number" name="payment" class="form-control" value="{{ $sale->payment }}" required>

                               <div class="form-group">
                                <label for="user">choose user</label>
                                <select class="form-control" name="user" id="user">
                                    @foreach($users as $user)
                                  <option value="{{$user->id}}"
                                   @if($user->id == $sale->user_id) 
                                   <?php echo "selected"  ?>
                                   @endif >            
                                        {{$user->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                       

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
